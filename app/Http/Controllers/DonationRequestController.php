<?php

namespace App\Http\Controllers;

use App\Models\DonationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DonationRequestRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DonationRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $donationRequests = DonationRequest::paginate();

        return view('donation-request.index', compact('donationRequests'))
            ->with('i', ($request->input('page', 1) - 1) * $donationRequests->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $donationRequest = new DonationRequest();

        return view('donation-request.create', compact('donationRequest'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DonationRequestRequest $request): RedirectResponse
    {
        DonationRequest::create($request->validated());

        return Redirect::route('donation-requests.index')
            ->with('success', 'DonationRequest created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $donationRequest = DonationRequest::find($id);

        return view('donation-request.show', compact('donationRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $donationRequest = DonationRequest::find($id);

        return view('donation-request.edit', compact('donationRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DonationRequestRequest $request, DonationRequest $donationRequest): RedirectResponse
    {
        $donationRequest->update($request->validated());

        return Redirect::route('donation-requests.index')
            ->with('success', 'DonationRequest updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        DonationRequest::find($id)->delete();

        return Redirect::route('donation-requests.index')
            ->with('success', 'DonationRequest deleted successfully');
    }
}
