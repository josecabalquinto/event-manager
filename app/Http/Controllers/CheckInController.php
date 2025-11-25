<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CheckInController extends Controller
{
    public function index()
    {
        $events = Event::published()
            ->upcoming()
            ->orderBy('event_date')
            ->get();

        return Inertia::render('Admin/CheckIn/Index', [
            'events' => $events,
        ]);
    }

    public function scan(Request $request)
    {
        try {
            $validated = $request->validate([
                'qr_code' => 'required|string|min:1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('QR Code validation failed', [
                'qr_code' => $request->qr_code,
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code format.',
                'errors' => $e->errors(),
            ], 422);
        }

        // Trim and clean the QR code
        $qrCode = trim($request->qr_code);

        Log::info('QR Code scan attempt', [
            'qr_code' => $qrCode,
            'length' => strlen($qrCode),
            'admin_id' => Auth::id(),
        ]);

        $registration = EventRegistration::where('qr_code', $qrCode)
            ->with(['event', 'user', 'checkIn'])
            ->first();

        if (!$registration) {
            Log::warning('QR Code not found', ['qr_code' => $qrCode]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code. Registration not found.',
            ], 404);
        }

        // Check if already checked in
        if ($registration->isCheckedIn()) {
            Log::info('Already checked in', [
                'qr_code' => $qrCode,
                'user' => $registration->user->name ?? $registration->guest_name,
                'event' => $registration->event->title,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'This attendee is already checked in!',
                'registration' => [
                    'user' => $registration->user->name ?? $registration->guest_name,
                    'email' => $registration->user->email ?? $registration->guest_email,
                    'student_id' => $registration->user->student_id ?? $registration->guest_student_id,
                    'event' => $registration->event->title,
                    'checked_in_at' => $registration->checkIn->checked_in_at->format('M d, Y \a\t h:i A'),
                ],
            ], 400);
        }

        // Create check-in record
        $checkIn = CheckIn::create([
            'event_id' => $registration->event_id,
            'event_registration_id' => $registration->id,
            'checked_in_by' => Auth::id(),
            'checked_in_at' => now(),
        ]);

        Log::info('Check-in successful', [
            'check_in_id' => $checkIn->id,
            'user' => $registration->user->name ?? $registration->guest_name,
            'event' => $registration->event->title,
            'admin' => Auth::user()->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => '✓ Check-in successful!',
            'registration' => [
                'user' => $registration->user->name ?? $registration->guest_name,
                'email' => $registration->user->email ?? $registration->guest_email,
                'student_id' => $registration->user->student_id ?? $registration->guest_student_id,
                'course' => $registration->user->course ?? $registration->guest_course,
                'year_level' => $registration->user->year_level ?? $registration->guest_year_level,
                'section' => $registration->user->section ?? $registration->guest_section,
                'event' => $registration->event->title,
                'checked_in_at' => now()->format('M d, Y \a\t h:i A'),
            ],
        ]);
    }

    public function eventCheckIns(Event $event)
    {
        $checkIns = CheckIn::where('event_id', $event->id)
            ->with(['registration.user', 'checkedInBy'])
            ->orderBy('checked_in_at', 'desc')
            ->get()
            ->map(function ($checkIn) {
                return [
                    'id' => $checkIn->id,
                    'checked_in_at' => $checkIn->checked_in_at->format('Y-m-d H:i:s'),
                    'user' => [
                        'name' => $checkIn->registration->user->name,
                        'email' => $checkIn->registration->user->email,
                    ],
                    'checked_in_by' => [
                        'name' => $checkIn->checkedInBy->name,
                    ],
                ];
            });

        return Inertia::render('Admin/CheckIn/EventCheckIns', [
            'event' => $event,
            'check_ins' => $checkIns,
        ]);
    }
}
