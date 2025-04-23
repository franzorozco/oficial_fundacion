<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $events = Event::paginate();

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

        return Redirect::route('events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $event = Event::find($id);

        return view('event.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $event = Event::find($id);

        return view('event.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, Event $event): RedirectResponse
    {
        $event->update($request->validated());

        return Redirect::route('events.index')
            ->with('success', 'Event updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Event::find($id)->delete();

        return Redirect::route('events.index')
            ->with('success', 'Event deleted successfully');
    }
}
