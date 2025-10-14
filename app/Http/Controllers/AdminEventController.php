<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventType;
use App\Models\EventOrganizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')
            ->withCount('registrations')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'event_date' => $event->event_date->format('Y-m-d'),
                    'event_time' => $event->event_time,
                    'location' => $event->location,
                    'max_participants' => $event->max_participants,
                    'registrations_count' => $event->registrations_count,
                    'is_published' => $event->is_published,
                    'has_certificate' => $event->has_certificate,
                    'created_at' => $event->created_at,
                    'updated_at' => $event->updated_at,
                ];
            });

        return Inertia::render('Admin/Events/Index', [
            'events' => $events,
        ]);
    }

    public function create()
    {
        $eventTypes = EventType::active()->ordered()->get();
        $eventOrganizers = EventOrganizer::active()->ordered()->get();

        return Inertia::render('Admin/Events/Create', [
            'eventTypes' => $eventTypes,
            'eventOrganizers' => $eventOrganizers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'banner_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'max_participants' => 'nullable|integer|min:1',
            'event_type_id' => 'nullable|exists:event_types,id',
            'event_organizer_id' => 'nullable|exists:event_organizers,id',
            'is_published' => 'boolean',
            // Certificate fields
            'has_certificate' => 'boolean',
            'certificate_title' => 'nullable|string|max:255',
            'certificate_description' => 'nullable|string',
            'certificate_template' => 'nullable|string',
            'certificate_signatories' => 'nullable|array',
            'certificate_signatories.*.name' => 'nullable|string|max:255',
            'certificate_signatories.*.title' => 'nullable|string|max:255',
        ]);

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('event-banners', 'public');
        }

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        $eventTypes = EventType::active()->ordered()->get();
        $eventOrganizers = EventOrganizer::active()->ordered()->get();

        return Inertia::render('Admin/Events/Edit', [
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'event_date' => $event->event_date->format('Y-m-d'),
                'event_time' => $event->event_time,
                'location' => $event->location,
                'latitude' => $event->latitude,
                'longitude' => $event->longitude,
                'banner_image' => $event->banner_image,
                'banner_image_url' => $event->banner_image_url,
                'max_participants' => $event->max_participants,
                'event_type_id' => $event->event_type_id,
                'event_organizer_id' => $event->event_organizer_id,
                'is_published' => $event->is_published,
                // Certificate fields
                'has_certificate' => $event->has_certificate,
                'certificate_title' => $event->certificate_title,
                'certificate_description' => $event->certificate_description,
                'certificate_template' => $event->certificate_template,
                'certificate_signatories' => $event->certificate_signatories ?? [],
                'registrations_count' => $event->registrations()->count(),
                'checked_in_count' => $event->checked_in_participants_count,
                'certificates_generated_count' => $event->certificates_generated_count,
            ],
            'eventTypes' => $eventTypes,
            'eventOrganizers' => $eventOrganizers,
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'banner_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'remove_banner_image' => 'nullable|boolean',
            'max_participants' => 'nullable|integer|min:1',
            'event_type_id' => 'nullable|exists:event_types,id',
            'event_organizer_id' => 'nullable|exists:event_organizers,id',
            'is_published' => 'nullable|boolean',
            // Certificate fields
            'has_certificate' => 'nullable|boolean',
            'certificate_title' => 'nullable|string|max:255',
            'certificate_description' => 'nullable|string',
            'certificate_template' => 'nullable|string',
            'certificate_signatories' => 'nullable|array',
            'certificate_signatories.*.name' => 'nullable|string|max:255',
            'certificate_signatories.*.title' => 'nullable|string|max:255',
        ]);

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            // Delete old image if exists
            if ($event->banner_image) {
                Storage::disk('public')->delete($event->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('event-banners', 'public');
        } elseif ($request->has('remove_banner_image') && $request->remove_banner_image) {
            // Remove banner image if requested
            if ($event->banner_image) {
                Storage::disk('public')->delete($event->banner_image);
            }
            $validated['banner_image'] = null;
        } else {
            // Keep existing banner image
            unset($validated['banner_image']);
        }

        // Handle checkbox fields that might not be present
        if (!$request->has('is_published')) {
            $validated['is_published'] = false;
        }

        if (!$request->has('has_certificate')) {
            $validated['has_certificate'] = false;
        }

        // Certificate fields are now handled without color/style options

        // Remove the remove_banner_image flag from validated data
        unset($validated['remove_banner_image']);

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
