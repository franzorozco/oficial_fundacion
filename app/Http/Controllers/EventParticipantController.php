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
        $eventParticipants = EventParticipant::paginate();

        return view('event-participant.index', compact('eventParticipants'))
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
        EventParticipant::create($request->validated());

        return Redirect::route('event-participants.index')
            ->with('success', 'EventParticipant created successfully.');
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
