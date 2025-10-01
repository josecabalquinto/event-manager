<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::published()
            ->upcoming()
            ->orderBy('event_date')
            ->with('registrations')
            ->get()
            ->map(function ($event) {
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
                ];
            });

        return Inertia::render('Events/Index', [
            'events' => $events,
        ]);
    }

    public function show(Event $event)
    {
        if (!$event->is_published) {
            abort(404);
        }

        $event->load('registrations');

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
            ],
        ]);
    }
}
