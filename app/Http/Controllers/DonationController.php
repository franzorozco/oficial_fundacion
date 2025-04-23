<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DonationRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $donations = Donation::paginate();

        return view('donation.index', compact('donations'))
            ->with('i', ($request->input('page', 1) - 1) * $donations->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $donation = new Donation();

        return view('donation.create', compact('donation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DonationRequest $request): RedirectResponse
    {
        Donation::create($request->validated());

        return Redirect::route('donations.index')
            ->with('success', 'Donation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $donation = Donation::find($id);

        return view('donation.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $donation = Donation::find($id);

        return view('donation.edit', compact('donation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DonationRequest $request, Donation $donation): RedirectResponse
    {
        $donation->update($request->validated());

        return Redirect::route('donations.index')
            ->with('success', 'Donation updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Donation::find($id)->delete();

        return Redirect::route('donations.index')
            ->with('success', 'Donation deleted successfully');
    }
}
