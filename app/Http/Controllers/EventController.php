<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Campaign;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Event::with(['campaign', 'user']);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }

        $events = $query->paginate();

        return view('event.index', compact('events'))
            ->with('i', ($request->input('page', 1) - 1) * $events->perPage());
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $event = new Event();

        return view('event.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request): RedirectResponse
    {   
        Event::create($request->validated());

        return redirect()->back()->with('success', 'Evento creado exitosamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $event = Event::with([
            'eventParticipants',
            'eventLocations',
            'eventLocationsTrashed' => function ($query) {
                $query->onlyTrashed();
            }
        ])->findOrFail($id);

        $users = User::whereDoesntHave('eventParticipants', function ($query) use ($id) {
            $query->where('event_id', $id);
        })->get();

        return view('event.show', compact('event', 'users'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $event = Event::findOrFail($id);
        $campaigns = Campaign::pluck('name', 'id'); // id => name
        $users = User::pluck('name', 'id');         // id => name

        return view('event.edit', compact('event', 'campaigns', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, Event $event): RedirectResponse
    {
        $event->update($request->validated());

        return redirect()->back()->with('success', 'Evento actualizado exitosamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        Event::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Evento eliminado exitosamente.');
    }

    public function trashed(Request $request): View
    {
        $events = Event::onlyTrashed() // Solo los eventos eliminados (soft deleted)
            ->with(['campaign', 'user'])
            ->paginate();

        return view('event.trashed', compact('events'))
            ->with('i', ($request->input('page', 1) - 1) * $events->perPage());
    }

    public function restore($id): RedirectResponse
    {
        $event = Event::withTrashed()->findOrFail($id);
        $event->restore();

        return redirect()->back()->with('success', 'Evento restaurado exitosamente.');
    }


    public function forceDelete($id): RedirectResponse
    {
        $event = Event::withTrashed()->findOrFail($id);
        $event->forceDelete();

        return redirect()->back()->with('success', 'Evento eliminado permanentemente.');
    }



}
