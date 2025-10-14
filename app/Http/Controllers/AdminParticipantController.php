<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminParticipantController extends Controller
{

    /**
     * Display a listing of all participants across events
     */
    public function index(Request $request)
    {
        $query = EventRegistration::with(['event', 'user', 'checkIn'])
            ->orderBy('created_at', 'desc');

        // Filter by event
        if ($request->has('event_id') && $request->event_id) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by check-in status
        if ($request->has('checked_in') && $request->checked_in !== '') {
            if ($request->checked_in === '1') {
                $query->whereHas('checkIn');
            } else {
                $query->whereDoesntHave('checkIn');
            }
        }

        // Search by participant name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('guest_name', 'like', "%{$search}%")
                    ->orWhere('guest_email', 'like', "%{$search}%");
            });
        }

        $participants = $query->paginate(20)->through(function ($registration) {
            return [
                'id' => $registration->id,
                'qr_code' => $registration->qr_code,
                'status' => $registration->status,
                'is_checked_in' => $registration->isCheckedIn(),
                'registration_date' => $registration->created_at->format('M d, Y'),
                'check_in_time' => $registration->checkIn ? $registration->checkIn->created_at->format('M d, Y H:i') : null,
                'event' => [
                    'id' => $registration->event->id,
                    'title' => $registration->event->title,
                    'event_date' => $registration->event->event_date->format('M d, Y'),
                    'event_time' => $registration->event->event_time,
                    'location' => $registration->event->location,
                ],
                'participant' => $registration->user ? [
                    'id' => $registration->user->id,
                    'name' => $registration->user->name,
                    'email' => $registration->user->email,
                    'student_id' => $registration->user->student_id,
                    'course' => $registration->user->course,
                    'year_level' => $registration->user->year_level,
                    'section' => $registration->user->section,
                    'type' => 'registered',
                ] : [
                    'id' => null,
                    'name' => $registration->guest_name,
                    'email' => $registration->guest_email,
                    'student_id' => $registration->guest_student_id,
                    'course' => $registration->guest_course,
                    'year_level' => $registration->guest_year_level,
                    'section' => $registration->guest_section,
                    'type' => 'guest',
                ],
            ];
        });

        // Get all events for filter dropdown
        $events = Event::orderBy('event_date', 'desc')
            ->get(['id', 'title', 'event_date']);

        // Get statistics
        $stats = [
            'total_participants' => EventRegistration::count(),
            'pending' => EventRegistration::where('status', 'pending')->count(),
            'approved' => EventRegistration::where('status', 'approved')->count(),
            'rejected' => EventRegistration::where('status', 'rejected')->count(),
            'checked_in' => EventRegistration::whereHas('checkIn')->count(),
        ];

        return Inertia::render('Admin/Participants/Index', [
            'participants' => $participants,
            'events' => $events,
            'filters' => $request->only(['event_id', 'status', 'checked_in', 'search']),
            'stats' => $stats,
        ]);
    }

    /**
     * Show detailed view of a specific participant
     */
    public function show(EventRegistration $registration)
    {
        $registration->load(['event', 'user', 'checkIn', 'certificate']);

        return Inertia::render('Admin/Participants/Show', [
            'registration' => [
                'id' => $registration->id,
                'qr_code' => $registration->qr_code,
                'status' => $registration->status,
                'is_checked_in' => $registration->isCheckedIn(),
                'registration_date' => $registration->created_at->format('M d, Y H:i'),
                'check_in_time' => $registration->checkIn ? $registration->checkIn->created_at->format('M d, Y H:i') : null,
                'approved_by' => $registration->approved_by ? User::find($registration->approved_by)->name : null,
                'approved_at' => $registration->approved_at ? $registration->approved_at->format('M d, Y H:i') : null,
                'has_certificate' => $registration->hasCertificate(),
                'event' => [
                    'id' => $registration->event->id,
                    'title' => $registration->event->title,
                    'description' => $registration->event->description,
                    'event_date' => $registration->event->event_date->format('M d, Y'),
                    'event_time' => $registration->event->event_time,
                    'location' => $registration->event->location,
                    'max_participants' => $registration->event->max_participants,
                ],
                'participant' => $registration->user ? [
                    'id' => $registration->user->id,
                    'name' => $registration->user->name,
                    'email' => $registration->user->email,
                    'student_id' => $registration->user->student_id,
                    'course' => $registration->user->course,
                    'year_level' => $registration->user->year_level,
                    'section' => $registration->user->section,
                    'type' => 'registered',
                ] : [
                    'id' => null,
                    'name' => $registration->guest_name,
                    'email' => $registration->guest_email,
                    'student_id' => $registration->guest_student_id,
                    'course' => $registration->guest_course,
                    'year_level' => $registration->guest_year_level,
                    'section' => $registration->guest_section,
                    'type' => 'guest',
                ],
                'certificate' => $registration->certificate ? [
                    'id' => $registration->certificate->id,
                    'certificate_number' => $registration->certificate->certificate_number,
                    'is_generated' => $registration->certificate->is_generated,
                    'completion_date' => $registration->certificate->completion_date->format('M d, Y'),
                ] : null,
            ],
        ]);
    }

    /**
     * Approve a registration
     */
    public function approve(EventRegistration $registration)
    {
        $registration->approve(Auth::user());

        return back()->with('success', 'Registration approved successfully.');
    }

    /**
     * Reject a registration
     */
    public function reject(EventRegistration $registration)
    {
        $registration->reject(Auth::user());

        return back()->with('success', 'Registration rejected successfully.');
    }

    /**
     * Remove a registration (delete)
     */
    public function destroy(EventRegistration $registration)
    {
        $participantName = $registration->user ? $registration->user->name : $registration->guest_name;
        $eventTitle = $registration->event->title;

        // Delete related records
        if ($registration->checkIn) {
            $registration->checkIn->delete();
        }
        if ($registration->certificate) {
            $registration->certificate->delete();
        }

        $registration->delete();

        return back()->with('success', "Registration for {$participantName} in '{$eventTitle}' has been removed.");
    }

    /**
     * Bulk approve registrations
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'registration_ids' => 'required|array',
            'registration_ids.*' => 'exists:event_registrations,id',
        ]);

        $updated = EventRegistration::whereIn('id', $request->registration_ids)
            ->where('status', 'pending')
            ->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

        return back()->with('success', "Successfully approved {$updated} registrations.");
    }

    /**
     * Bulk reject registrations
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'registration_ids' => 'required|array',
            'registration_ids.*' => 'exists:event_registrations,id',
        ]);

        $updated = EventRegistration::whereIn('id', $request->registration_ids)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

        return back()->with('success', "Successfully rejected {$updated} registrations.");
    }

    /**
     * Bulk delete registrations
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'registration_ids' => 'required|array',
            'registration_ids.*' => 'exists:event_registrations,id',
        ]);

        $registrations = EventRegistration::whereIn('id', $request->registration_ids)->get();

        foreach ($registrations as $registration) {
            // Delete related records
            if ($registration->checkIn) {
                $registration->checkIn->delete();
            }
            if ($registration->certificate) {
                $registration->certificate->delete();
            }
            $registration->delete();
        }

        $count = count($request->registration_ids);
        return back()->with('success', "Successfully removed {$count} registrations.");
    }

    /**
     * Generate attendance report for an event
     */
    public function attendanceReport(Event $event)
    {
        $event->load(['registrations.user', 'registrations.checkIn']);

        $participants = $event->registrations->map(function ($registration) {
            return [
                'name' => $registration->user ? $registration->user->name : $registration->guest_name,
                'email' => $registration->user ? $registration->user->email : $registration->guest_email,
                'student_id' => $registration->user ? $registration->user->student_id : $registration->guest_student_id,
                'course' => $registration->user ? $registration->user->course : $registration->guest_course,
                'year_level' => $registration->user ? $registration->user->year_level : $registration->guest_year_level,
                'section' => $registration->user ? $registration->user->section : $registration->guest_section,
                'status' => $registration->status,
                'is_checked_in' => $registration->isCheckedIn(),
                'check_in_time' => $registration->checkIn ? $registration->checkIn->created_at->format('M d, Y H:i:s') : null,
                'registration_date' => $registration->created_at->format('M d, Y H:i:s'),
                'type' => $registration->user ? 'Registered User' : 'Guest Registration',
            ];
        });

        // Generate PDF using the AttendanceReportService
        $pdf = app(\App\Services\AttendanceReportService::class)->generateReport($event, $participants);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="attendance-report-' . $event->id . '.pdf"',
        ]);
    }
}
