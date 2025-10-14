<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = EventRegistration::with(['event', 'user', 'approvedBy'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by event
        if ($request->has('event_id') && $request->event_id) {
            $query->where('event_id', $request->event_id);
        }

        $registrations = $query->paginate(20)->withQueryString();

        $events = Event::orderBy('title')->get(['id', 'title']);

        return Inertia::render('Admin/Registrations/Index', [
            'registrations' => $registrations,
            'events' => $events,
            'filters' => [
                'status' => $request->status,
                'event_id' => $request->event_id,
            ],
        ]);
    }

    public function show(EventRegistration $registration)
    {
        $registration->load(['event', 'user', 'approvedBy', 'checkIn', 'certificate']);

        return Inertia::render('Admin/Registrations/Show', [
            'registration' => [
                'id' => $registration->id,
                'status' => $registration->status,
                'qr_code' => $registration->qr_code,
                'created_at' => $registration->created_at->format('Y-m-d H:i:s'),
                'approved_at' => $registration->approved_at?->format('Y-m-d H:i:s'),
                'rejection_reason' => $registration->rejection_reason,
                'is_checked_in' => $registration->isCheckedIn(),
                'has_certificate' => $registration->hasCertificate(),
                'event' => [
                    'id' => $registration->event->id,
                    'title' => $registration->event->title,
                    'event_date' => $registration->event->event_date->format('Y-m-d'),
                    'event_time' => $registration->event->event_time,
                    'location' => $registration->event->location,
                ],
                'registrant' => [
                    'name' => $registration->registrant_name,
                    'email' => $registration->registrant_email,
                    'student_id' => $registration->registrant_student_id,
                    'course' => $registration->registrant_course,
                    'year_level' => $registration->registrant_year_level,
                    'section' => $registration->registrant_section,
                    'full_info' => $registration->registrant_full_info,
                    'is_guest' => $registration->isGuestRegistration(),
                ],
                'approved_by' => $registration->approvedBy ? [
                    'name' => $registration->approvedBy->name,
                    'email' => $registration->approvedBy->email,
                ] : null,
            ],
        ]);
    }

    public function approve(EventRegistration $registration)
    {
        if (!$registration->isPending()) {
            return back()->with('error', 'Registration is not pending approval.');
        }

        $registration->approve(Auth::user());

        return back()->with('success', 'Registration approved successfully.');
    }

    public function reject(Request $request, EventRegistration $registration)
    {
        if (!$registration->isPending()) {
            return back()->with('error', 'Registration is not pending approval.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $registration->reject(Auth::user(), $request->reason);

        return back()->with('success', 'Registration rejected.');
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'registration_ids' => 'required|array',
            'registration_ids.*' => 'exists:event_registrations,id',
        ]);

        $registrations = EventRegistration::whereIn('id', $request->registration_ids)
            ->where('status', 'pending')
            ->get();

        $approvedCount = 0;
        foreach ($registrations as $registration) {
            $registration->approve(Auth::user());
            $approvedCount++;
        }

        return back()->with('success', "Successfully approved {$approvedCount} registration(s).");
    }

    public function bulkApproveByEvent(Request $request, Event $event)
    {
        $registrations = EventRegistration::where('event_id', $event->id)
            ->where('status', 'pending')
            ->get();

        $approvedCount = 0;
        foreach ($registrations as $registration) {
            $registration->approve(Auth::user());
            $approvedCount++;
        }

        return back()->with('success', "Successfully approved {$approvedCount} pending registration(s) for event: {$event->title}");
    }
}
