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
USE FPDF;
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



    public function exportPdf(Request $request)
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

        $donations = $query->get();

        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Donations Cashes Report', 0, 1, 'C');
        $pdf->Ln(5);

        // Header
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 10, 'No', 1);
        $pdf->Cell(30, 10, 'Donor', 1);
        $pdf->Cell(40, 10, 'External Donor', 1);
        $pdf->Cell(25, 10, 'Amount', 1);
        $pdf->Cell(25, 10, 'Method', 1);
        $pdf->Cell(30, 10, 'Date', 1);
        $pdf->Cell(30, 10, 'Campaign', 1);
        $pdf->Ln();

        // Rows
        $pdf->SetFont('Arial', '', 9);
        $i = 1;
        foreach ($donations as $donation) {
            $pdf->Cell(10, 8, $i++, 1);
            $pdf->Cell(30, 8, substr($donation->user->name ?? 'N/A', 0, 20), 1);
            $pdf->Cell(40, 8, substr($donation->external_donor->names ?? 'N/A', 0, 30), 1);
            $pdf->Cell(25, 8, $donation->amount, 1);
            $pdf->Cell(25, 8, $donation->method, 1);
            $pdf->Cell(30, 8, $donation->donation_date, 1);
            $pdf->Cell(30, 8, substr($donation->campaign->name ?? 'N/A', 0, 20), 1);
            $pdf->Ln();
        }

        $pdf->Output('I', 'donations_cashes.pdf');
        exit;
    }


    // Mostrar eliminados
    public function trashed(Request $request): View
    {
        $donationsCashes = DonationsCash::onlyTrashed()->paginate(10);
        return view('donations-cash.trashed', compact('donationsCashes'))
            ->with('i', ($request->input('page', 1) - 1) * $donationsCashes->perPage());
    }

    // Restaurar
    public function restore($id): RedirectResponse
    {
        DonationsCash::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('donations-cashes.trashed')->with('success', 'Donación restaurada correctamente.');
    }

    // Eliminar permanentemente
    public function forceDelete($id): RedirectResponse
    {
        DonationsCash::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('donations-cashes.trashed')->with('success', 'Donación eliminada permanentemente.');
    }

}
