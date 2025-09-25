<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;
use App\Models\Event;
use App\Models\User;
use App\Models\Campaign;  // Asegúrate de incluir este modelo
use Illuminate\Http\Request;
use App\Http\Requests\EventParticipantRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

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
        // Obtener eventos, usuarios y campañas
        $events = Event::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $campaigns = Campaign::pluck('name', 'id');  // Obtener las campañas

        // Crear una nueva instancia de EventParticipant para el formulario de creación
        $eventParticipant = new EventParticipant();

        return view('event-participant.create', compact('events', 'users', 'campaigns', 'eventParticipant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventParticipantRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['registration_date'] = now();

        // Validar que la ubicación pertenezca al evento
        $location = \App\Models\EventLocation::where('id', $data['event_locations_id'])
            ->where('event_id', $data['event_id'])
            ->first();

        if (!$location) {
            return Redirect::back()->withErrors([
                'event_locations_id' => 'La ubicación seleccionada no pertenece a este evento.'
            ]);
        }

        EventParticipant::create($data);

        return back()->with('success', 'Participante agregado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $eventParticipant = EventParticipant::findOrFail($id);

        return view('event-participant.show', compact('eventParticipant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventParticipant $eventParticipant): View
    {
        $events = Event::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $campaigns = Campaign::pluck('name', 'id');  // Obtener las campañas
        $eventLocations = \App\Models\EventLocation::where('event_id', $eventParticipant->event_id)->pluck('location_name', 'id');

        return view('event-participant.edit', [
            'eventParticipant' => $eventParticipant,
            'events' => $events,
            'users' => $users,
            'campaigns' => $campaigns,
            'eventLocations' => $eventLocations,  // Pasar las ubicaciones a la vista
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(EventParticipantRequest $request, EventParticipant $eventParticipant): RedirectResponse
    {
        $eventParticipant->update($request->validated());

        return back()->with('success', 'Participante actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        EventParticipant::findOrFail($id)->delete();

        return back()->with('success', 'Participante eliminado correctamente.');
    }

    public function getEventsByCampaign($campaignId)
    {
        $events = Event::where('campaign_id', $campaignId)->pluck('name', 'id');
        return response()->json(['events' => $events]);
    }

    public function getLocationsByEvent($eventId)
    {
        $locations = EventLocation::where('event_id', $eventId)->pluck('name', 'id');
        return response()->json(['locations' => $locations]);
    }

    public function restore($id)
    {
        $participant = EventParticipant::withTrashed()->findOrFail($id);
        $participant->restore();

        return back()->with('success', 'Participante restaurado exitosamente.');
    }

    public function forceDelete($id)
    {
        $participant = EventParticipant::withTrashed()->findOrFail($id);
        $participant->forceDelete();

        return back()->with('success', 'Participante eliminado permanentemente.');
    }


}
