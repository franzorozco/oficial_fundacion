<?php

namespace App\Http\Controllers;

use App\Models\DonationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DonationRequestRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Donation;

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
    // DonationRequestController.php

    public function create()
    {
        $users = User::all(); // o aplica filtros si es necesario
        $donations = Donation::all(); // si usas esta variable también
        $donationRequest = new DonationRequest(); // para mantener compatibilidad con el old()

        return view('donation-request.create', compact('users', 'donations', 'donationRequest'));
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
        $users = User::all(); // Obtener todos los usuarios
        $donations = Donation::all(); // Obtener todas las donaciones
    
        return view('donation-request.edit', compact('donationRequest', 'users', 'donations'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(DonationRequestRequest $request, DonationRequest $donationRequest): RedirectResponse
    {
        // Actualiza la solicitud de donación con los datos validados
        $donationRequest->update([
            'applicant_user__id' => $request->input('applicant_user__id'),
            'user_in_charge_id' => $request->input('user_in_charge_id'),
            'donation_id' => $request->input('donation_id'),
            'request_date' => $request->input('request_date'),
            'notes' => $request->input('notes'),
            'state' => $request->input('state'),
        ]);
        
        
        
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
