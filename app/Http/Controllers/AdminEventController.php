<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')
            ->withCount('registrations')
            ->get();

        return Inertia::render('Admin/Events/Index', [
            'events' => $events,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Events/Create');
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
            'is_published' => 'boolean',
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
                'is_published' => $event->is_published,
            ],
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
            'is_published' => 'nullable|boolean',
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

        // Handle checkbox field that might not be present
        if (!$request->has('is_published')) {
            $validated['is_published'] = false;
        }

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
