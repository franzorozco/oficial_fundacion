<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EventParticipantRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EventParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = EventParticipant::query();

        // Filtros
        if ($request->filled('search')) {
            $query->where('observations', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $eventParticipants = $query->paginate(10)->appends($request->all());

        // Extraer IDs únicos de eventos para los filtros
        $uniqueEventIds = EventParticipant::select('event_id')->distinct()->pluck('event_id');

        return view('event-participant.index', compact('eventParticipants', 'uniqueEventIds'))
            ->with('i', ($request->input('page', 1) - 1) * $eventParticipants->perPage());
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $eventParticipant = new EventParticipant();

        return view('event-participant.create', compact('eventParticipant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventParticipantRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['registration_date'] = now();
    
        // Verifica que la ubicación pertenezca al evento
        $location = \App\Models\EventLocation::where('id', $data['event_locations_id'])
            ->where('event_id', $data['event_id'])
            ->first();
    
        if (!$location) {
            return Redirect::back()->withErrors(['event_locations_id' => 'La ubicación seleccionada no pertenece a este evento.']);
        }
    
        EventParticipant::create($data);
    
        return Redirect::route('events.show', $data['event_id'])
            ->with('success', 'Participante agregado correctamente.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $eventParticipant = EventParticipant::find($id);

        return view('event-participant.show', compact('eventParticipant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $eventParticipant = EventParticipant::find($id);

        return view('event-participant.edit', compact('eventParticipant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventParticipantRequest $request, EventParticipant $eventParticipant): RedirectResponse
    {
        $eventParticipant->update($request->validated());

        return Redirect::route('event-participants.index')
            ->with('success', 'EventParticipant updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        EventParticipant::find($id)->delete();

        return Redirect::route('event-participants.index')
            ->with('success', 'EventParticipant deleted successfully');
    }
}
