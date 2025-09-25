<?php

namespace App\Http\Controllers;

use App\Models\EventLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EventLocationRequest;
use Illuminate\View\View;
use App\Models\Event; // Asegúrate de importar el modelo Event
class EventLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $eventId = $request->input('event_id');
        $startFrom = $request->input('start_from');
        $startTo = $request->input('start_to');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $eventLocations = EventLocation::query()
            ->when($search, function ($query, $search) {
                return $query->where('location_name', 'LIKE', "%{$search}%")
                    ->orWhere('address', 'LIKE', "%{$search}%")
                    ->orWhere('event_id', 'LIKE', "%{$search}%");
            })
            ->when($eventId, fn($query) => $query->where('event_id', $eventId))
            ->when($startFrom, fn($query) => $query->where('start_hour', '>=', $startFrom))
            ->when($startTo, fn($query) => $query->where('start_hour', '<=', $startTo))
            ->when($dateFrom, fn($query) => $query->whereDate('start_hour', '>=', $dateFrom))
            ->when($dateTo, fn($query) => $query->whereDate('start_hour', '<=', $dateTo))
            ->paginate()
            ->appends($request->query());

        return view('event-location.index', compact('eventLocations'))
            ->with('i', ($request->input('page', 1) - 1) * $eventLocations->perPage());
    }

    public function create(): View
    {
        $eventLocation = new EventLocation();
        $events = Event::all(); // Asegúrate de importar el modelo Event
        return view('event-location.create', compact('eventLocation', 'events'));
    }

    public function store(EventLocationRequest $request): RedirectResponse
    {
        EventLocation::create($request->validated());

        return back()->with('success', 'EventLocation created successfully.');
    }

    public function show($id): View
    {
        $eventLocation = EventLocation::find($id);

        return view('event-location.show', compact('eventLocation'));
    }

    public function edit($id)
    {
        $eventLocation = EventLocation::findOrFail($id);

        // Obtén todos los eventos (puedes aplicar filtros o paginación si quieres)
        $events = Event::all();

        return view('event-location.edit', compact('eventLocation', 'events'));
    }

    public function update(EventLocationRequest $request, EventLocation $eventLocation): RedirectResponse
    {
        $eventLocation->update($request->validated());

        return back()->with('success', 'EventLocation updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        EventLocation::find($id)->delete();

        return back()->with('success', 'EventLocation deleted successfully');
    }

    public function trashed(Request $request): View
    {
        $eventLocations = EventLocation::onlyTrashed()->paginate();
        return view('event-location.trashed', compact('eventLocations'))
            ->with('i', ($request->input('page', 1) - 1) * $eventLocations->perPage());
    }

    public function restore($id)
    {
        $location = EventLocation::withTrashed()->findOrFail($id);
        $location->restore();

        return back()->with('success', 'Ubicación restaurada con éxito.');
    }

    public function forceDelete($id)
    {
        $location = EventLocation::withTrashed()->findOrFail($id);
        $location->forceDelete();

        return back()->with('success', 'Ubicación eliminada permanentemente.');
    }

}
