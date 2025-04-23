<?php

namespace App\Http\Controllers;

use App\Models\DonationItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DonationItemRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DonationItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $donationItems = DonationItem::paginate();

        return view('donation-item.index', compact('donationItems'))
            ->with('i', ($request->input('page', 1) - 1) * $donationItems->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $donationItem = new DonationItem();

        return view('donation-item.create', compact('donationItem'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DonationItemRequest $request): RedirectResponse
    {
        DonationItem::create($request->validated());

        return Redirect::route('donation-items.index')
            ->with('success', 'DonationItem created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $donationItem = DonationItem::find($id);

        return view('donation-item.show', compact('donationItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $donationItem = DonationItem::find($id);

        return view('donation-item.edit', compact('donationItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DonationItemRequest $request, DonationItem $donationItem): RedirectResponse
    {
        $donationItem->update($request->validated());

        return Redirect::route('donation-items.index')
            ->with('success', 'DonationItem updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        DonationItem::find($id)->delete();

        return Redirect::route('donation-items.index')
            ->with('success', 'DonationItem deleted successfully');
    }
}
