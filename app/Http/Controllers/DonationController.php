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
use FPDF;
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

        $users = User::select('id', 'name')->get(); // corregido
        $externalDonors = ExternalDonor::select('id', 'names')->get();
        $statuses = DonationStatus::select('id', 'name')->get(); // corregido
        $campaigns = Campaign::select('id', 'name')->get(); // corregido

        // Asumiendo que "received_by" es un usuario, puedes reutilizar $users
        $receivers = $users;

        return view('donation.create', compact('donation', 'users', 'externalDonors', 'statuses', 'campaigns', 'receivers'));
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

    public function generatePdf($id)
    {
        // Obtener la donación y los ítems asociados
        $donation = Donation::with(['user', 'receivedBy', 'externalDonor', 'status', 'campaign', 'items'])->findOrFail($id);
        
        // Crear una instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Título
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Donation Details', 0, 1, 'C');
        $pdf->Ln(10);

        // Información principal de la donación
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Donor: ' . ($donation->externalDonor->names ?? 'N/A'), 0, 1);
        $pdf->Cell(0, 10, 'Received By: ' . ($donation->receivedBy->name ?? 'N/A'), 0, 1);
        $pdf->Cell(0, 10, 'Campaign: ' . ($donation->campaign->name ?? 'N/A'), 0, 1);
        $pdf->Cell(0, 10, 'Donation Date: ' . $donation->donation_date, 0, 1);
        $pdf->Cell(0, 10, 'Status: ' . ($donation->status->name ?? 'N/A'), 0, 1);
        $pdf->Cell(0, 10, 'Notes: ' . ($donation->notes ?? 'N/A'), 0, 1);
        $pdf->Ln(10);

        // Encabezado para los ítems
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(30, 10, 'Item Name', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Quantity', 1, 0, 'C');
        $pdf->Cell(50, 10, 'Unit', 1, 0, 'C');
        $pdf->Cell(60, 10, 'Description', 1, 1, 'C');

        // Detalles de los ítems
        $pdf->SetFont('Arial', '', 12);
        foreach ($donation->items as $item) {
            $pdf->Cell(30, 10, $item->item_name, 1, 0, 'C');
            $pdf->Cell(30, 10, $item->quantity, 1, 0, 'C');
            $pdf->Cell(50, 10, $item->unit, 1, 0, 'C');
            $pdf->Cell(60, 10, $item->description, 1, 1, 'C');
        }

        // Forzar la descarga del PDF
        $pdf->Output('D', 'donation_details_' . $donation->id . '.pdf');
    }

    public function generateAllDonationsPdf()
    {
        // Obtener todas las donaciones con sus relaciones necesarias
        $donations = Donation::with(['user', 'receivedBy', 'externalDonor', 'status', 'campaign'])->get();
        
        // Crear una instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Título
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'List of All Donations', 0, 1, 'C');
        $pdf->Ln(10);

        // Encabezado de la tabla
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(20, 10, 'No', 1, 0, 'C');
        $pdf->Cell(40, 10, 'External Donor', 1, 0, 'C');
        $pdf->Cell(40, 10, 'User', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Received By', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Status', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Campaign', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Donation Date', 1, 1, 'C');

        // Detalles de las donaciones
        $pdf->SetFont('Arial', '', 12);
        foreach ($donations as $index => $donation) {
            $pdf->Cell(20, 10, ++$index, 1, 0, 'C');
            $pdf->Cell(40, 10, $donation->externalDonor->names ?? '-', 1, 0, 'C');
            $pdf->Cell(40, 10, $donation->user->name ?? '-', 1, 0, 'C');
            $pdf->Cell(40, 10, $donation->receivedBy->name ?? '-', 1, 0, 'C');
            $pdf->Cell(40, 10, $donation->status->name ?? '-', 1, 0, 'C');
            $pdf->Cell(40, 10, $donation->campaign->name ?? '-', 1, 0, 'C');
            $pdf->Cell(40, 10, $donation->donation_date, 1, 1, 'C');
        }

        // Forzar la descarga del PDF
        $pdf->Output('D', 'all_donations.pdf');
    }


    // Mostrar donaciones eliminadas
    public function trashed(Request $request): View
    {
        $donations = Donation::onlyTrashed()->with(['user', 'receivedBy', 'externalDonor', 'status', 'campaign'])->paginate();

        return view('donation.trashed', compact('donations'))
            ->with('i', ($request->input('page', 1) - 1) * $donations->perPage());
    }

    // Restaurar donación
    public function restore($id): RedirectResponse
    {
        $donation = Donation::onlyTrashed()->findOrFail($id);
        $donation->restore();

        return redirect()->route('donations.trashed')->with('success', 'Donación restaurada correctamente.');
    }

    // Eliminar permanentemente
    public function forceDelete($id): RedirectResponse
    {
        $donation = Donation::onlyTrashed()->findOrFail($id);
        $donation->forceDelete();

        return redirect()->route('donations.trashed')->with('success', 'Donación eliminada permanentemente.');
    }







}
