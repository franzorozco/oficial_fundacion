<?php

namespace App\Http\Controllers;

use App\Models\ExternalDonor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ExternalDonorRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

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

    public function storeAjax(Request $request)
    {
        $validated = $request->validate([
            'names' => 'required|string|max:100',
            'paternal_surname' => 'nullable|string|max:100',
            'maternal_surname' => 'nullable|string|max:100',
            'email' => 'required|email|unique:external_donor,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $donor = ExternalDonor::create($validated);

        return response()->json([
            'success' => true,
            'donor' => $donor,
        ]);
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


    public function search(Request $request)
    {
        $query = $request->input('q');
        return ExternalDonor::where('names', 'LIKE', "%{$query}%")
            ->select('id', 'names')
            ->get();
    }




public function generatePDF(Request $request)
{
    $query = ExternalDonor::query();

    // 🔎 Filtros
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

    // 📊 Datos del reporte (AUDITORÍA)
    $reportData = [
        'date' => now()->format('d/m/Y'),
        'time' => now()->format('H:i'),
        'generated_by' => Auth::user()->name ?? 'Sistema',
        'generated_email' => Auth::user()->email ?? '-',
        'ip' => $request->ip(),
        'user_agent' => $request->header('User-Agent'),
        'total' => $externalDonors->count(),
    ];

    $pdf = Pdf::loadView('pdf.external-donors', compact('externalDonors', 'reportData'))
        ->setPaper('a4', 'landscape');

    return $pdf->stream('reporte_donantes_externos.pdf');
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
