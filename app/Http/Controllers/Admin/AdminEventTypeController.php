<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminEventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eventTypes = EventType::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/EventTypes/Index', [
            'eventTypes' => $eventTypes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/EventTypes/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:event_types',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        EventType::create([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.event-types.index')
            ->with('success', 'Event type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EventType $eventType)
    {
        return Inertia::render('Admin/EventTypes/Show', [
            'eventType' => $eventType->load('events')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventType $eventType)
    {
        return Inertia::render('Admin/EventTypes/Edit', [
            'eventType' => $eventType
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventType $eventType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:event_types,name,' . $eventType->id,
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $eventType->update([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.event-types.index')
            ->with('success', 'Event type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventType $eventType)
    {
        // Check if event type is being used by any events
        if ($eventType->events()->exists()) {
            return redirect()->route('admin.event-types.index')
                ->with('error', 'Cannot delete event type that is being used by events.');
        }

        $eventType->delete();

        return redirect()->route('admin.event-types.index')
            ->with('success', 'Event type deleted successfully.');
    }
}
