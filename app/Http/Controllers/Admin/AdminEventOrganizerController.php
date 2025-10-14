<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventOrganizer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminEventOrganizerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eventOrganizers = EventOrganizer::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/EventOrganizers/Index', [
            'eventOrganizers' => $eventOrganizers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/EventOrganizers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:event_organizers',
            'department' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        EventOrganizer::create([
            'name' => $request->name,
            'department' => $request->department,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'description' => $request->description,
            'color' => $request->color,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.event-organizers.index')
            ->with('success', 'Event organizer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EventOrganizer $eventOrganizer)
    {
        return Inertia::render('Admin/EventOrganizers/Show', [
            'eventOrganizer' => $eventOrganizer->load('events')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventOrganizer $eventOrganizer)
    {
        return Inertia::render('Admin/EventOrganizers/Edit', [
            'eventOrganizer' => $eventOrganizer
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventOrganizer $eventOrganizer)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:event_organizers,name,' . $eventOrganizer->id,
            'department' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $eventOrganizer->update([
            'name' => $request->name,
            'department' => $request->department,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'description' => $request->description,
            'color' => $request->color,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.event-organizers.index')
            ->with('success', 'Event organizer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventOrganizer $eventOrganizer)
    {
        // Check if event organizer is being used by any events
        if ($eventOrganizer->events()->exists()) {
            return redirect()->route('admin.event-organizers.index')
                ->with('error', 'Cannot delete event organizer that is being used by events.');
        }

        $eventOrganizer->delete();

        return redirect()->route('admin.event-organizers.index')
            ->with('success', 'Event organizer deleted successfully.');
    }
}
