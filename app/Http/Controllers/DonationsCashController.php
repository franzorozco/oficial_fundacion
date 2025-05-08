<?php

namespace App\Http\Controllers;
use App\Models\ExternalDonor; // Asegúrate de importar el modelo

use App\Models\DonationsCash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DonationsCashRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Campaign;

class DonationsCashController extends Controller
{
    /** 
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = DonationsCash::query();

        if ($search = $request->input('search')) {
            $query->whereHas('user', fn($q) => 
                    $q->where('name', 'LIKE', "%{$search}%")
                )
                ->orWhereHas('external_donor', fn($q) => 
                    $q->where('names', 'LIKE', "%{$search}%")
                )
                ->orWhere('method', 'LIKE', "%{$search}%")
                ->orWhere('amount', 'LIKE', "%{$search}%")
                ->orWhereHas('campaign', fn($q) =>
                    $q->where('name', 'LIKE', "%{$search}%")
                );
        }

        $donationsCashes = $query->paginate(10);

        return view('donations-cash.index', compact('donationsCashes'))
            ->with('i', ($request->input('page', 1) - 1) * $donationsCashes->perPage());
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $donationsCash = new DonationsCash();
        $donors = User::all(); // Para obtener la lista de donantes
        $campaigns = Campaign::all(); // Para obtener la lista de campañas
        $externalDonors = ExternalDonor::all();
        return view('donations-cash.create', compact('donationsCash', 'donors', 'campaigns', 'externalDonors'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(DonationsCashRequest $request): RedirectResponse
    {
        DonationsCash::create($request->validated());
        
        $donation = DonationsCash::create($request->validated());
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
    public function edit($id)
    {
        $donationsCash = DonationsCash::findOrFail($id);
        $campaigns = Campaign::all();
        $donors = User::all(); // 👈 Agrega esto también
        $externalDonors = ExternalDonor::all();
        return view('donations-cash.edit', compact('donationsCash', 'campaigns', 'donors', 'externalDonors'));
        
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
