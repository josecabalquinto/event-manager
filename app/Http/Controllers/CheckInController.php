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
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }

        $registration = EventRegistration::where('qr_code', $request->qr_code)
            ->with(['event', 'user', 'checkIn'])
            ->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code.',
            ], 404);
        }

        if ($registration->isCheckedIn()) {
            return response()->json([
                'success' => false,
                'message' => 'Already checked in.',
                'registration' => [
                    'user' => $registration->user->name,
                    'event' => $registration->event->title,
                    'checked_in_at' => $registration->checkIn->checked_in_at->format('Y-m-d H:i:s'),
                ],
            ], 400);
        }

        CheckIn::create([
            'event_id' => $registration->event_id,
            'event_registration_id' => $registration->id,
            'checked_in_by' => Auth::id(),
            'checked_in_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully checked in!',
            'registration' => [
                'user' => $registration->user->name,
                'event' => $registration->event->title,
                'checked_in_at' => now()->format('Y-m-d H:i:s'),
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
