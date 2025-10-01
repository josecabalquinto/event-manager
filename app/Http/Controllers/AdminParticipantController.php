<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminParticipantController extends Controller
{
    public function index(Request $request)
    {
        $query = EventRegistration::with(['event', 'user', 'checkIn'])
            ->orderBy('created_at', 'desc');

        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $participants = $query->get()->map(function ($registration) {
            return [
                'id' => $registration->id,
                'qr_code' => $registration->qr_code,
                'status' => $registration->status,
                'is_checked_in' => $registration->isCheckedIn(),
                'created_at' => $registration->created_at->format('Y-m-d H:i:s'),
                'user' => [
                    'id' => $registration->user->id,
                    'name' => $registration->user->name,
                    'email' => $registration->user->email,
                ],
                'event' => [
                    'id' => $registration->event->id,
                    'title' => $registration->event->title,
                    'event_date' => $registration->event->event_date->format('Y-m-d'),
                ],
            ];
        });

        return Inertia::render('Admin/Participants/Index', [
            'participants' => $participants,
        ]);
    }

    public function show(EventRegistration $registration)
    {
        $registration->load(['event', 'user', 'checkIn.checkedInBy']);

        return Inertia::render('Admin/Participants/Show', [
            'participant' => [
                'id' => $registration->id,
                'qr_code' => $registration->qr_code,
                'status' => $registration->status,
                'is_checked_in' => $registration->isCheckedIn(),
                'created_at' => $registration->created_at->format('Y-m-d H:i:s'),
                'user' => [
                    'id' => $registration->user->id,
                    'name' => $registration->user->name,
                    'email' => $registration->user->email,
                ],
                'event' => [
                    'id' => $registration->event->id,
                    'title' => $registration->event->title,
                    'description' => $registration->event->description,
                    'event_date' => $registration->event->event_date->format('Y-m-d'),
                    'event_time' => $registration->event->event_time,
                    'location' => $registration->event->location,
                ],
                'check_in' => $registration->checkIn ? [
                    'checked_in_at' => $registration->checkIn->checked_in_at->format('Y-m-d H:i:s'),
                    'checked_in_by' => [
                        'name' => $registration->checkIn->checkedInBy->name,
                    ],
                ] : null,
            ],
        ]);
    }
}
