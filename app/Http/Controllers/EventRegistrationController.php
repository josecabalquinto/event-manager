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
            ->with(['event', 'checkIn'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($registration) {
                return [
                    'id' => $registration->id,
                    'qr_code' => $registration->qr_code,
                    'status' => $registration->status,
                    'is_checked_in' => $registration->isCheckedIn(),
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

        $registration->load(['event', 'checkIn']);

        return Inertia::render('MyEvents/Show', [
            'registration' => [
                'id' => $registration->id,
                'qr_code' => $registration->qr_code,
                'status' => $registration->status,
                'is_checked_in' => $registration->isCheckedIn(),
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
            if (EventRegistration::where('event_id', $event->id)->where('user_id', $user->id)->exists()) {
                return back()->with('error', 'You are already registered for this event.');
            }

            $registration = EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
            ]);

            return redirect()->route('my-events.show', $registration)
                ->with('success', 'Successfully registered for the event!');
        } else {
            // Guest registration - create account and register
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            Auth::login($user);

            $registration = EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
            ]);

            return redirect()->route('my-events.show', $registration)
                ->with('success', 'Account created and successfully registered for the event!');
        }
    }
}
