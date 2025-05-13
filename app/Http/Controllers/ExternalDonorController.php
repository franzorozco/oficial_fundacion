<?php

namespace App\Http\Controllers;

use App\Models\ExternalDonor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ExternalDonorRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use FPDF;

class ExternalDonorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = ExternalDonor::query();

        // Filtros por búsqueda general
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('names', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filtro por apellido paterno
        if ($request->filled('paternal')) {
            $query->where('paternal_surname', 'like', '%' . $request->paternal . '%');
        }

        // Filtro por apellido materno
        if ($request->filled('maternal')) {
            $query->where('maternal_surname', 'like', '%' . $request->maternal . '%');
        }

        $externalDonors = $query->paginate(10);

        return view('external-donor.index', compact('externalDonors'))
            ->with('i', ($request->input('page', 1) - 1) * $externalDonors->perPage());
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $externalDonor = new ExternalDonor();

        return view('external-donor.create', compact('externalDonor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExternalDonorRequest $request): RedirectResponse
    {
        ExternalDonor::create($request->validated());

        return Redirect::route('external-donors.index')
            ->with('success', 'ExternalDonor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $externalDonor = ExternalDonor::find($id);

        return view('external-donor.show', compact('externalDonor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $externalDonor = ExternalDonor::find($id);

        return view('external-donor.edit', compact('externalDonor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExternalDonorRequest $request, ExternalDonor $externalDonor): RedirectResponse
    {
        $externalDonor->update($request->validated());

        return Redirect::route('external-donors.index')
            ->with('success', 'ExternalDonor updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        ExternalDonor::find($id)->delete();

        return Redirect::route('external-donors.index')
            ->with('success', 'ExternalDonor deleted successfully');
    }

    public function generatePDF(Request $request)
    {
        // Crear una nueva instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);

        // Título
        $pdf->Cell(200, 10, 'Reporte de Donantes Externos', 0, 1, 'C');
        $pdf->Ln(10);

        // Añadir los filtros activos
        if ($request->filled('search') || $request->filled('paternal') || $request->filled('maternal')) {
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(50, 10, 'Filtros aplicados:');
            $filters = [];
            if ($request->filled('search')) $filters[] = 'Búsqueda: ' . $request->search;
            if ($request->filled('paternal')) $filters[] = 'Apellido Paterno: ' . $request->paternal;
            if ($request->filled('maternal')) $filters[] = 'Apellido Materno: ' . $request->maternal;
            $pdf->MultiCell(0, 10, implode(', ', $filters));
            $pdf->Ln(5);
        }

        // Encabezado de la tabla
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 10, 'No', 1);
        $pdf->Cell(40, 10, 'Names', 1);
        $pdf->Cell(40, 10, 'Paternal Surname', 1);
        $pdf->Cell(40, 10, 'Maternal Surname', 1);
        $pdf->Cell(50, 10, 'Email', 1);
        $pdf->Cell(30, 10, 'Phone', 1);
        $pdf->Cell(50, 10, 'Address', 1);
        $pdf->Ln();

        // Obtener los donantes según los filtros
        $query = ExternalDonor::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('names', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('paternal')) {
            $query->where('paternal_surname', 'like', '%' . $request->paternal . '%');
        }

        if ($request->filled('maternal')) {
            $query->where('maternal_surname', 'like', '%' . $request->maternal . '%');
        }

        $externalDonors = $query->get();

        // Imprimir los datos de los donantes
        $pdf->SetFont('Arial', '', 10);
        foreach ($externalDonors as $key => $externalDonor) {
            $pdf->Cell(10, 10, $key + 1, 1);
            $pdf->Cell(40, 10, $externalDonor->names, 1);
            $pdf->Cell(40, 10, $externalDonor->paternal_surname, 1);
            $pdf->Cell(40, 10, $externalDonor->maternal_surname, 1);
            $pdf->Cell(50, 10, $externalDonor->email, 1);
            $pdf->Cell(30, 10, $externalDonor->phone, 1);
            $pdf->Cell(50, 10, $externalDonor->address, 1);
            $pdf->Ln();
        }

        // Enviar los encabezados para la descarga del archivo PDF
        return response()->stream(function() use ($pdf) {
            $pdf->Output('I', 'donantes_externos.pdf');
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="donantes_externos.pdf"'
        ]);
    }


    public function trashed(): View
    {
        $trashedDonors = ExternalDonor::onlyTrashed()->paginate(10);
        return view('external-donor.trashed', compact('trashedDonors'));
    }

    public function restore($id): RedirectResponse
    {
        $donor = ExternalDonor::onlyTrashed()->findOrFail($id);
        $donor->restore();

        return Redirect::route('external-donors.trashed')->with('success', 'Donador restaurado correctamente.');
    }

    public function forceDelete($id): RedirectResponse
    {
        $donor = ExternalDonor::onlyTrashed()->findOrFail($id);
        $donor->forceDelete();

        return Redirect::route('external-donors.trashed')->with('success', 'Donador eliminado permanentemente.');
    }
}
