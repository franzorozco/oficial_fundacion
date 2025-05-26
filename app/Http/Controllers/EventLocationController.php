<?php

namespace App\Http\Controllers;

use App\Models\EventLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EventLocationRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EventLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $eventLocations = EventLocation::when($search, function ($query, $search) {
            return $query->where('location_name', 'LIKE', "%{$search}%")
                        ->orWhere('address', 'LIKE', "%{$search}%")
                        ->orWhere('event_id', 'LIKE', "%{$search}%");
        })->paginate();

        return view('event-location.index', compact('eventLocations'))
            ->with('i', ($request->input('page', 1) - 1) * $eventLocations->perPage());
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $eventLocation = new EventLocation();

        return view('event-location.create', compact('eventLocation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventLocationRequest $request): RedirectResponse
    {
        EventLocation::create($request->validated());

        return Redirect::route('event-locations.index')
            ->with('success', 'EventLocation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $eventLocation = EventLocation::find($id);

        return view('event-location.show', compact('eventLocation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $eventLocation = EventLocation::find($id);

        return view('event-location.edit', compact('eventLocation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventLocationRequest $request, EventLocation $eventLocation): RedirectResponse
    {
        $eventLocation->update($request->validated());

        return Redirect::route('event-locations.index')
            ->with('success', 'EventLocation updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        EventLocation::find($id)->delete();

        return Redirect::route('event-locations.index')
            ->with('success', 'EventLocation deleted successfully');
    }

    public function trashed(Request $request): View
    {
        $eventLocations = EventLocation::onlyTrashed()->paginate();
        return view('event-location.trashed', compact('eventLocations'))
            ->with('i', ($request->input('page', 1) - 1) * $eventLocations->perPage());
    }

    public function restore($id): RedirectResponse
    {
        $eventLocation = EventLocation::onlyTrashed()->findOrFail($id);
        $eventLocation->restore();

        return redirect()->route('event-locations.trashed')
            ->with('success', 'Ubicación restaurada exitosamente.');
    }

    public function forceDelete($id): RedirectResponse
    {
        $eventLocation = EventLocation::onlyTrashed()->findOrFail($id);
        $eventLocation->forceDelete();

        return redirect()->route('event-locations.trashed')
            ->with('success', 'Ubicación eliminada permanentemente.');
    }
}
