<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\User;
use App\Models\ExternalDonor;
use App\Models\DonationStatus;
use App\Models\Campaign;
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
        $donations = Donation::with(['user', 'receivedBy', 'externalDonor', 'status', 'campaign'])->paginate();

        return view('donation.index', compact('donations'))
            ->with('i', ($request->input('page', 1) - 1) * $donations->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $donation = new Donation();

        $users = User::pluck('name', 'id');
        $externalDonors = ExternalDonor::pluck('names', 'id');
        $statuses = DonationStatus::pluck('name', 'id');
        $campaigns = Campaign::pluck('name', 'id');

        return view('donation.create', compact('donation', 'users', 'externalDonors', 'statuses', 'campaigns'));
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
        $donation = Donation::with(['user', 'receivedBy', 'externalDonor', 'status', 'campaign', 'items', 'items.donation_type'])->findOrFail($id);

        return view('donation.show', compact('donation'));
        
    }
   
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $donation = Donation::findOrFail($id);
    
        // Obtener los usuarios, donantes externos, estados y campañas
        $users = User::all();  // Devuelve la colección completa de usuarios
        $externalDonors = ExternalDonor::all();  // Devuelve la colección completa de donantes externos
        $statuses = DonationStatus::all();  // Devuelve la colección completa de estados
        $campaigns = Campaign::all();  // Devuelve la colección completa de campañas
    
        // Si los "receivers" son usuarios, asigna la lista de usuarios a la variable $receivers
        $receivers = $users; // Aquí puedes personalizar la asignación si es necesario
    
        return view('donation.edit', compact('donation', 'users', 'externalDonors', 'statuses', 'campaigns', 'receivers'));
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        Donation::findOrFail($id)->delete();

        return Redirect::route('donations.index')
            ->with('success', 'Donation deleted successfully');
    }
}
