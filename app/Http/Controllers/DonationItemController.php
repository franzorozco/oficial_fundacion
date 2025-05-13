<?php

namespace App\Http\Controllers;

use App\Models\DonationItem;
use App\Models\DonationItemPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DonationItemRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\DonationType;

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
        $types = DonationType::all();

        return view('donation-item.create', compact('donationItem', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DonationItemRequest $request): RedirectResponse
    {
        // Crear ítem de donación
        $item = DonationItem::create($request->validated());

        // Guardar fotografía si se envió
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('items_donations', 'public');

            DonationItemPhoto::create([
                'donation_item_id' => $item->id,
                'photo_url' => basename($path),
            ]);
        }

        // Redirige a la donación correspondiente o al index si viene de ahí
        return redirect()->back()->with('success', 'Ítem agregado correctamente.');
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
        $types = DonationType::all();

        return view('donation-item.edit', compact('donationItem', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DonationItemRequest $request, DonationItem $donationItem): RedirectResponse
    {
        $donationItem->update($request->validated());

        return back()->with('success', 'DonationItem updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        DonationItem::find($id)->delete();

        return Redirect::route('donation-items.index')
            ->with('success', 'DonationItem deleted successfully');
    }
}
