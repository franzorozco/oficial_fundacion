<?php

namespace App\Http\Controllers;

use App\Models\DonationsCash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DonationsCashRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DonationsCashController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $donationsCashes = DonationsCash::paginate();

        return view('donations-cash.index', compact('donationsCashes'))
            ->with('i', ($request->input('page', 1) - 1) * $donationsCashes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $donationsCash = new DonationsCash();

        return view('donations-cash.create', compact('donationsCash'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DonationsCashRequest $request): RedirectResponse
    {
        DonationsCash::create($request->validated());

        return Redirect::route('donations-cashes.index')
            ->with('success', 'DonationsCash created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $donationsCash = DonationsCash::find($id);

        return view('donations-cash.show', compact('donationsCash'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $donationsCash = DonationsCash::find($id);

        return view('donations-cash.edit', compact('donationsCash'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DonationsCashRequest $request, DonationsCash $donationsCash): RedirectResponse
    {
        $donationsCash->update($request->validated());

        return Redirect::route('donations-cashes.index')
            ->with('success', 'DonationsCash updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        DonationsCash::find($id)->delete();

        return Redirect::route('donations-cashes.index')
            ->with('success', 'DonationsCash deleted successfully');
    }
}
