<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\EventRegistration;
use App\Models\CheckIn;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get basic statistics
        $totalEvents = Event::count();
        $totalUsers = User::count();
        $totalRegistrations = EventRegistration::count();
        $totalCheckIns = CheckIn::count();
        $totalCertificates = Certificate::where('is_generated', true)->count();

        // Calculate overall attendance rate
        $attendanceRate = $totalRegistrations > 0 ? round(($totalCheckIns / $totalRegistrations) * 100, 1) : 0;

        // Get recent events with attendance data
        $recentEvents = Event::with(['registrations', 'checkIns'])
            ->orderBy('event_date', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($event) {
                $registrations = $event->registrations->count();
                $checkIns = $event->checkIns->count();
                $attendanceRate = $registrations > 0 ? round(($checkIns / $registrations) * 100, 1) : 0;

                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'event_date' => $event->event_date->format('M d, Y'),
                    'registrations' => $registrations,
                    'check_ins' => $checkIns,
                    'attendance_rate' => $attendanceRate,
                ];
            });

        // Monthly attendance data for charts (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');

            $eventsInMonth = Event::whereYear('event_date', $date->year)
                ->whereMonth('event_date', $date->month)
                ->with(['registrations', 'checkIns'])
                ->get();

            $monthRegistrations = $eventsInMonth->sum(function ($event) {
                return $event->registrations->count();
            });

            $monthCheckIns = $eventsInMonth->sum(function ($event) {
                return $event->checkIns->count();
            });

            $monthlyData[] = [
                'month' => $month,
                'registrations' => $monthRegistrations,
                'check_ins' => $monthCheckIns,
                'attendance_rate' => $monthRegistrations > 0 ? round(($monthCheckIns / $monthRegistrations) * 100, 1) : 0,
            ];
        }

        // Event status distribution
        $eventStatusData = [
            'published' => Event::where('is_published', true)->count(),
            'draft' => Event::where('is_published', false)->count(),
        ];

        // Registration status distribution
        $registrationStatusData = [
            'pending' => EventRegistration::where('status', 'pending')->count(),
            'approved' => EventRegistration::where('status', 'approved')->count(),
            'rejected' => EventRegistration::where('status', 'rejected')->count(),
        ];

        // Top performing events (by attendance rate)
        $topEvents = Event::with(['registrations', 'checkIns'])
            ->get()
            ->filter(function ($event) {
                return $event->registrations->count() > 0;
            })
            ->map(function ($event) {
                $registrations = $event->registrations->count();
                $checkIns = $event->checkIns->count();
                return [
                    'title' => $event->title,
                    'registrations' => $registrations,
                    'check_ins' => $checkIns,
                    'attendance_rate' => round(($checkIns / $registrations) * 100, 1),
                ];
            })
            ->sortByDesc('attendance_rate')
            ->take(5)
            ->values();

        return Inertia::render('Admin/Dashboard', [
            'statistics' => [
                'total_events' => $totalEvents,
                'total_users' => $totalUsers,
                'total_registrations' => $totalRegistrations,
                'total_check_ins' => $totalCheckIns,
                'total_certificates' => $totalCertificates,
                'overall_attendance_rate' => $attendanceRate,
            ],
            'recent_events' => $recentEvents,
            'monthly_data' => $monthlyData,
            'event_status_data' => $eventStatusData,
            'registration_status_data' => $registrationStatusData,
            'top_events' => $topEvents,
        ]);
    }
}
