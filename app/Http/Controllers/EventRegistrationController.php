<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class EventRegistrationController extends Controller
{
    public function index()
    {
        $registrations = EventRegistration::where('user_id', Auth::id())
            ->with(['event', 'checkIn', 'certificate'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($registration) {
                return [
                    'id' => $registration->id,
                    'qr_code' => $registration->qr_code,
                    'status' => $registration->status,
                    'is_checked_in' => $registration->isCheckedIn(),
                    'has_certificate' => $registration->hasCertificate(),
                    'certificate' => $registration->certificate ? [
                        'id' => $registration->certificate->id,
                        'certificate_number' => $registration->certificate->certificate_number,
                        'is_generated' => $registration->certificate->is_generated,
                        'certificate_url' => $registration->certificate->certificate_url,
                    ] : null,
                    'event' => [
                        'id' => $registration->event->id,
                        'title' => $registration->event->title,
                        'event_date' => $registration->event->event_date->format('Y-m-d'),
                        'event_time' => $registration->event->event_time,
                        'location' => $registration->event->location,
                    ],
                ];
            });

        return Inertia::render('MyEvents/Index', [
            'registrations' => $registrations,
        ]);
    }

    public function show(EventRegistration $registration)
    {
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }

        $registration->load(['event', 'checkIn', 'certificate']);

        return Inertia::render('MyEvents/Show', [
            'registration' => [
                'id' => $registration->id,
                'qr_code' => $registration->qr_code,
                'status' => $registration->status,
                'is_checked_in' => $registration->isCheckedIn(),
                'has_certificate' => $registration->hasCertificate(),
                'certificate' => $registration->certificate ? [
                    'id' => $registration->certificate->id,
                    'certificate_number' => $registration->certificate->certificate_number,
                    'is_generated' => $registration->certificate->is_generated,
                    'certificate_url' => $registration->certificate->certificate_url,
                    'completion_date' => $registration->certificate->completion_date->format('Y-m-d'),
                ] : null,
                'event' => [
                    'id' => $registration->event->id,
                    'title' => $registration->event->title,
                    'description' => $registration->event->description,
                    'event_date' => $registration->event->event_date->format('Y-m-d'),
                    'event_time' => $registration->event->event_time,
                    'location' => $registration->event->location,
                ],
            ],
        ]);
    }

    public function store(Request $request, Event $event)
    {
        if ($event->isFull()) {
            return back()->with('error', 'This event is already full.');
        }

        // Check if user is authenticated
        if (Auth::check()) {
            // Registered user
            $user = Auth::user();

            // Check if already registered
            if (EventRegistration::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->exists()
            ) {
                return back()->with('error', 'You are already registered for this event.');
            }

            $registration = EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
            ]);

            return redirect()->route('my-events.show', $registration)
                ->with('success', 'Registration submitted successfully! Your registration is pending approval from the administrator.');
        } else {
            // Determine if this is a guest registration or account creation
            $registrationType = $request->input('registration_type', 'create_account');

            if ($registrationType === 'guest') {
                // Guest registration - no account creation
                $validated = $request->validate([
                    'guest_name' => 'required|string|max:255',
                    'guest_email' => 'required|string|email|max:255',
                    'guest_student_id' => 'required|string|regex:/^\d{2}-\d{4}$/',
                    'guest_course' => 'required|in:BSIT,BSIS,BLIS,BSEMC',
                    'guest_year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year',
                    'guest_section' => 'required|string|max:10',
                ]);

                // Check for duplicate guest registration with same email and student ID
                if (EventRegistration::where('event_id', $event->id)
                    ->where('guest_email', $validated['guest_email'])
                    ->exists()
                ) {
                    return back()->with('error', 'This email is already registered for this event.');
                }

                if (EventRegistration::where('event_id', $event->id)
                    ->where('guest_student_id', $validated['guest_student_id'])
                    ->exists()
                ) {
                    return back()->with('error', 'This student ID is already registered for this event.');
                }

                $registration = EventRegistration::create([
                    'event_id' => $event->id,
                    'user_id' => null,
                    'guest_name' => $validated['guest_name'],
                    'guest_email' => $validated['guest_email'],
                    'guest_student_id' => $validated['guest_student_id'],
                    'guest_course' => $validated['guest_course'],
                    'guest_year_level' => $validated['guest_year_level'],
                    'guest_section' => $validated['guest_section'],
                ]);

                return redirect()->route('events.show', $event)
                    ->with('success', 'Registration submitted successfully! Your registration is pending approval from the administrator. QR Code: ' . $registration->qr_code);
            } else {
                // Account creation and registration
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:8|confirmed',
                    'student_id' => 'required|string|regex:/^\d{2}-\d{4}$/|unique:users',
                    'course' => 'required|in:BSIT,BSIS,BLIS,BSEMC',
                    'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year',
                    'section' => 'required|string|max:10',
                ]);

                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'student_id' => $validated['student_id'],
                    'course' => $validated['course'],
                    'year_level' => $validated['year_level'],
                    'section' => $validated['section'],
                ]);

                Auth::login($user);

                $registration = EventRegistration::create([
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                ]);

                return redirect()->route('my-events.show', $registration)
                    ->with('success', 'Account created successfully! Your registration is pending approval from the administrator.');
            }
        }
    }
}
