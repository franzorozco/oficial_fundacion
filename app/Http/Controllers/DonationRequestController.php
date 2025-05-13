<?php

namespace App\Http\Controllers;
use FPDF;
use Illuminate\Support\Str;

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

    public function exportPdf(Request $request)
    {
        $query = DonationRequest::query();

        if ($search = $request->input('search')) {
            $query->where('notes', 'LIKE', "%{$search}%")
                ->orWhere('state', 'LIKE', "%{$search}%")
                ->orWhereHas('user', fn($q) =>
                    $q->where('name', 'LIKE', "%{$search}%")
                )
                ->orWhereHas('userInCharge', fn($q) =>
                    $q->where('name', 'LIKE', "%{$search}%")
                );
        }

        // Asegura que las relaciones necesarias estén disponibles
        $donationRequests = $query->with(['user', 'userInCharge', 'donation'])->get();

        $pdf = new Fpdf();
        $pdf->AddPage('L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, utf8_decode('Reporte de Solicitudes de Donación'), 0, 1, 'C');

        // Encabezados de tabla
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 8, 'No', 1);
        $pdf->Cell(40, 8, 'Solicitante', 1);
        $pdf->Cell(40, 8, 'Encargado', 1);
        $pdf->Cell(25, 8, 'Donación ID', 1);
        $pdf->Cell(30, 8, 'Fecha', 1);
        $pdf->Cell(60, 8, 'Notas', 1);
        $pdf->Cell(25, 8, 'Estado', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 10);
        $i = 1;
        foreach ($donationRequests as $req) {
            $pdf->Cell(10, 8, $i++, 1);
            $pdf->Cell(40, 8, utf8_decode(optional($req->user)->name ?? 'N/A'), 1);
            $pdf->Cell(40, 8, utf8_decode(optional($req->userInCharge)->name ?? 'N/A'), 1);
            $pdf->Cell(25, 8, $req->donation_id ?? 'N/A', 1);
            $pdf->Cell(30, 8, $req->request_date, 1);
            $pdf->Cell(60, 8, utf8_decode(Str::limit($req->notes, 35)), 1);
            $pdf->Cell(25, 8, $req->state, 1);
            $pdf->Ln();
        }

        $pdf->Output();
        exit;
    }

    public function trashed(Request $request): View
    {
        $donationRequests = DonationRequest::onlyTrashed()->paginate();

        return view('donation-request.trashed', compact('donationRequests'))
            ->with('i', ($request->input('page', 1) - 1) * $donationRequests->perPage());
    }

    public function restore($id): RedirectResponse
    {
        $donationRequest = DonationRequest::withTrashed()->findOrFail($id);
        $donationRequest->restore();

        return Redirect::route('donation-requests.trashed')->with('success', 'Solicitud restaurada exitosamente.');
    }

    public function forceDelete($id): RedirectResponse
    {
        $donationRequest = DonationRequest::withTrashed()->findOrFail($id);
        $donationRequest->forceDelete();

        return Redirect::route('donation-requests.trashed')->with('success', 'Solicitud eliminada permanentemente.');
    }


}
