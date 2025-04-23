<?php

namespace App\Http\Controllers;

use App\Models\DonationRequestDescription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DonationRequestDescriptionRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DonationRequestDescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $donationRequestDescriptions = DonationRequestDescription::paginate();

        return view('donation-request-description.index', compact('donationRequestDescriptions'))
            ->with('i', ($request->input('page', 1) - 1) * $donationRequestDescriptions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $donationRequestDescription = new DonationRequestDescription();

        return view('donation-request-description.create', compact('donationRequestDescription'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DonationRequestDescriptionRequest $request): RedirectResponse
    {
        DonationRequestDescription::create($request->validated());

        return Redirect::route('donation-request-descriptions.index')
            ->with('success', 'DonationRequestDescription created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $donationRequestDescription = DonationRequestDescription::find($id);

        return view('donation-request-description.show', compact('donationRequestDescription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $donationRequestDescription = DonationRequestDescription::find($id);

        return view('donation-request-description.edit', compact('donationRequestDescription'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DonationRequestDescriptionRequest $request, DonationRequestDescription $donationRequestDescription): RedirectResponse
    {
        $donationRequestDescription->update($request->validated());

        return Redirect::route('donation-request-descriptions.index')
            ->with('success', 'DonationRequestDescription updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        DonationRequestDescription::find($id)->delete();

        return Redirect::route('donation-request-descriptions.index')
            ->with('success', 'DonationRequestDescription deleted successfully');
    }
}
