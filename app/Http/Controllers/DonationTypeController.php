<?php

namespace App\Http\Controllers;

use App\Models\DonationType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DonationTypeRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DonationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = DonationType::query();

        if ($request->has('search') && $request->search !== null) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('description', 'LIKE', "%{$searchTerm}%");
        }

        $donationTypes = $query->paginate();

        return view('donation-type.index', compact('donationTypes'))
            ->with('i', ($request->input('page', 1) - 1) * $donationTypes->perPage());
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $donationType = new DonationType();

        return view('donation-type.create', compact('donationType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DonationTypeRequest $request): RedirectResponse
    {
        DonationType::create($request->validated());

        return Redirect::route('donation-types.index')
            ->with('success', 'DonationType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $donationType = DonationType::findOrFail($id);
        

        return view('donation-type.show', compact('donationType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $donationType = DonationType::findOrFail($id);


        return view('donation-type.edit', compact('donationType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DonationTypeRequest $request, DonationType $donationType): RedirectResponse
{
    $donationType->update($request->validated());

    return Redirect::route('donation-types.index')
        ->with('success', 'El tipo de donación ha sido actualizado correctamente.');
}


public function destroy($id): RedirectResponse
{
    $donationType = DonationType::findOrFail($id);  // Cambiado a findOrFail

    $donationType->delete();

    return Redirect::route('donation-types.index')
        ->with('success', 'Tipo de donación eliminado correctamente');
}

}
