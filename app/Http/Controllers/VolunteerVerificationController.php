<?php

namespace App\Http\Controllers;

use App\Models\VolunteerVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\VolunteerVerificationRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use FPDF;

class VolunteerVerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = VolunteerVerification::query();

        // Filtrado por los parámetros de búsqueda
        if ($request->has('search') && $request->input('search') !== '') {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                })
                ->orWhere('document_type', 'like', '%' . $searchTerm . '%')
                ->orWhere('name_document', 'like', '%' . $searchTerm . '%');
            });
        }

        $volunteerVerifications = $query->paginate();

        return view('volunteer-verification.index', compact('volunteerVerifications'))
            ->with('i', ($request->input('page', 1) - 1) * $volunteerVerifications->perPage());
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $volunteerVerification = new VolunteerVerification();

        return view('volunteer-verification.create', compact('volunteerVerification'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VolunteerVerificationRequest $request): RedirectResponse
    {
        VolunteerVerification::create($request->validated());

        return Redirect::route('volunteer-verifications.index')
            ->with('success', 'VolunteerVerification created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $volunteerVerification = VolunteerVerification::find($id);

        return view('volunteer-verification.show', compact('volunteerVerification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $volunteerVerification = VolunteerVerification::find($id);

        return view('volunteer-verification.edit', compact('volunteerVerification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VolunteerVerificationRequest $request, VolunteerVerification $volunteerVerification): RedirectResponse
    {
        $volunteerVerification->update($request->validated());

        return Redirect::route('volunteer-verifications.index')
            ->with('success', 'VolunteerVerification updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        VolunteerVerification::find($id)->delete();

        return Redirect::route('volunteer-verifications.index')
            ->with('success', 'VolunteerVerification deleted successfully');
    }

    public function generatePdf(Request $request)
    {
        $query = VolunteerVerification::query();
    
        // Filtrado por los parámetros de búsqueda
        if ($request->has('search') && $request->input('search') !== '') {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                })
                ->orWhere('document_type', 'like', '%' . $searchTerm . '%')
                ->orWhere('name_document', 'like', '%' . $searchTerm . '%');
            });
        }
    
        $volunteerVerifications = $query->get();
    
        // Crear una nueva instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
    
        // Agregar un título
        $pdf->Cell(200, 10, 'Volunteer Verifications Report', 0, 1, 'C');
    
        // Agregar los encabezados de la tabla
        $pdf->Cell(20, 10, 'No', 1);
        $pdf->Cell(40, 10, 'User Name', 1);
        $pdf->Cell(40, 10, 'User Resp Name', 1);
        $pdf->Cell(40, 10, 'Document Type', 1);
        $pdf->Cell(40, 10, 'Document Url', 1);
        $pdf->Cell(40, 10, 'Name Document', 1);
        $pdf->Cell(30, 10, 'Status', 1);
        $pdf->Cell(50, 10, 'Comment', 1);
        $pdf->Ln();
    
        // Agregar los datos de cada verificación de voluntario
        foreach ($volunteerVerifications as $index => $verification) {
            $pdf->Cell(20, 10, $index + 1, 1);
            $pdf->Cell(40, 10, $verification->user ? $verification->user->name : 'N/A', 1);
            $pdf->Cell(40, 10, $verification->userResp ? $verification->userResp->name : 'N/A', 1);
            $pdf->Cell(40, 10, $verification->document_type, 1);
            $pdf->Cell(40, 10, $verification->document_url, 1);
            $pdf->Cell(40, 10, $verification->name_document, 1);
            $pdf->Cell(30, 10, $verification->status, 1);
            $pdf->Cell(50, 10, $verification->coment, 1);
            $pdf->Ln();
        }
    
        // Generar el archivo PDF y devolverlo como respuesta de flujo
        return response()->stream(function() use ($pdf) {
            $pdf->Output('I', 'volunteer_verifications_report.pdf');  // 'I' para mostrar en el navegador
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="volunteer_verifications_report.pdf"'
        ]);
    }
    
public function trashed(Request $request): View
{
    $volunteerVerifications = VolunteerVerification::onlyTrashed()->paginate();
    return view('volunteer-verification.trashed', compact('volunteerVerifications'))
        ->with('i', ($request->input('page', 1) - 1) * $volunteerVerifications->perPage());
}

public function restore($id): RedirectResponse
{
    $verification = VolunteerVerification::onlyTrashed()->findOrFail($id);
    $verification->restore();

    return redirect()->route('volunteer-verifications.trashed')
        ->with('success', 'Verificación restaurada correctamente.');
}

public function forceDelete($id): RedirectResponse
{
    $verification = VolunteerVerification::onlyTrashed()->findOrFail($id);
    $verification->forceDelete();

    return redirect()->route('volunteer-verifications.trashed')
        ->with('success', 'Verificación eliminada permanentemente.');
}


}
