<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userCourse = $user ? $user->course : null;

        $events = Event::published()
            ->upcoming()
            ->orderBy('event_date')
            ->with('registrations')
            ->get()
            ->filter(function ($event) use ($userCourse) {
                // If user is not logged in or has no course, show all events
                if (!$userCourse) {
                    return true;
                }

                // Check if event is allowed for user's course
                return $event->isAllowedForCourse($userCourse);
            })
            ->map(function ($event) use ($user) {
                $registration = null;
                if ($user) {
                    $registration = EventRegistration::where('event_id', $event->id)
                        ->where('user_id', $user->id)
                        ->first();
                }

                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'event_date' => $event->event_date->format('Y-m-d'),
                    'event_time' => $event->event_time,
                    'location' => $event->location,
                    'banner_image_url' => $event->banner_image_url,
                    'has_banner_image' => $event->hasBannerImage(),
                    'max_participants' => $event->max_participants,
                    'registered_count' => $event->registrations->count(),
                    'available_spots' => $event->available_spots,
                    'is_full' => $event->isFull(),
                    'allowed_courses' => $event->allowed_courses,
                    'is_registered' => $registration !== null,
                    'registered_at' => $registration ? $registration->created_at->format('M d, Y \a\t h:i A') : null,
                ];
            })
            ->values();

        return Inertia::render('Events/Index', [
            'events' => $events,
        ]);
    }

    public function show(Event $event)
    {
        if (!$event->is_published) {
            abort(404);
        }

        $user = Auth::user();
        $event->load('registrations');

        $registration = null;
        if ($user) {
            $registration = EventRegistration::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->first();
        }

        // Prepare user profile data if authenticated
        $userProfile = null;
        if ($user) {
            $userProfile = [
                'name' => $user->name,
                'email' => $user->email,
                'student_id' => $user->student_id,
                'course' => $user->course,
                'year_level' => $user->year_level,
                'section' => $user->section,
            ];
        }

        return Inertia::render('Events/Show', [
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'event_date' => $event->event_date->format('Y-m-d'),
                'event_time' => $event->event_time,
                'location' => $event->location,
                'latitude' => $event->latitude,
                'longitude' => $event->longitude,
                'banner_image_url' => $event->banner_image_url,
                'has_banner_image' => $event->hasBannerImage(),
                'max_participants' => $event->max_participants,
                'registered_count' => $event->registrations->count(),
                'available_spots' => $event->available_spots,
                'is_full' => $event->isFull(),
                'allowed_courses' => $event->allowed_courses,
                'is_registered' => $registration !== null,
                'registered_at' => $registration ? $registration->created_at->format('M d, Y \a\t h:i A') : null,
            ],
            'userProfile' => $userProfile,
        ]);
    }
}
