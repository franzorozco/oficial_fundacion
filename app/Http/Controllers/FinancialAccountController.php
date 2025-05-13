<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\FinancialAccountRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use FPDF;

class FinancialAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = FinancialAccount::query();

        // Filtros
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('type', 'like', '%' . $search . '%');
        }

        if ($request->has('min_balance')) {
            $query->where('balance', '>=', $request->input('min_balance'));
        }

        if ($request->has('max_balance')) {
            $query->where('balance', '<=', $request->input('max_balance'));
        }

        // Paginación con los filtros aplicados
        $financialAccounts = $query->paginate();

        return view('financial-account.index', compact('financialAccounts'))
            ->with('i', ($request->input('page', 1) - 1) * $financialAccounts->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $financialAccount = new FinancialAccount();

        return view('financial-account.create', compact('financialAccount'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FinancialAccountRequest $request): RedirectResponse
    {
        FinancialAccount::create($request->validated());

        return Redirect::route('financial-accounts.index')
            ->with('success', 'Cuenta financiera creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $financialAccount = FinancialAccount::find($id);

        return view('financial-account.show', compact('financialAccount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $financialAccount = FinancialAccount::find($id);

        return view('financial-account.edit', compact('financialAccount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FinancialAccountRequest $request, FinancialAccount $financialAccount): RedirectResponse
    {
        $financialAccount->update($request->validated());

        return Redirect::route('financial-accounts.index')
            ->with('success', 'Cuenta financiera actualizada correctamente');
    }

    /**
     * Soft delete the specified resource.
     */
    public function destroy($id): RedirectResponse
    {
        FinancialAccount::find($id)->delete();

        return Redirect::route('financial-accounts.index')
            ->with('success', 'Cuenta financiera eliminada correctamente');
    }

    /**
     * Generate PDF report for financial accounts.
     */
    public function generatePDF(Request $request)
    {
        // Crear una nueva instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);

        // Título
        $pdf->Cell(200, 10, 'Reporte de Cuentas Financieras', 0, 1, 'C');
        $pdf->Ln(10);

        // Añadir los filtros activos
        if ($request->filled('search') || $request->filled('min_balance') || $request->filled('max_balance')) {
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(50, 10, 'Filtros aplicados:');
            $filters = [];
            if ($request->filled('search')) $filters[] = 'Búsqueda: ' . $request->search;
            if ($request->filled('min_balance')) $filters[] = 'Balance Mínimo: ' . $request->min_balance;
            if ($request->filled('max_balance')) $filters[] = 'Balance Máximo: ' . $request->max_balance;
            $pdf->MultiCell(0, 10, implode(', ', $filters));
            $pdf->Ln(5);
        }

        // Encabezado de la tabla
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 10, 'No', 1);
        $pdf->Cell(60, 10, 'Nombre', 1);
        $pdf->Cell(40, 10, 'Tipo', 1);
        $pdf->Cell(30, 10, 'Balance', 1);
        $pdf->Cell(50, 10, 'Descripción', 1);
        $pdf->Ln();

        // Obtener las cuentas financieras según los filtros
        $query = FinancialAccount::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('type', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('min_balance')) {
            $query->where('balance', '>=', $request->min_balance);
        }

        if ($request->filled('max_balance')) {
            $query->where('balance', '<=', $request->max_balance);
        }

        $financialAccounts = $query->get();

        // Imprimir los datos de las cuentas financieras
        $pdf->SetFont('Arial', '', 10);
        foreach ($financialAccounts as $key => $financialAccount) {
            $pdf->Cell(10, 10, $key + 1, 1);
            $pdf->Cell(60, 10, $financialAccount->name, 1);
            $pdf->Cell(40, 10, $financialAccount->type, 1);
            $pdf->Cell(30, 10, number_format($financialAccount->balance, 2), 1);
            $pdf->Cell(50, 10, $financialAccount->description, 1);
            $pdf->Ln();
        }

        // Enviar los encabezados para la descarga del archivo PDF
        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="cuentas_financieras.pdf"');
    }

    /**
     * Show a list of trashed (soft deleted) financial accounts.
     */
    public function trashed(Request $request): View
    {
        // Obtener solo las cuentas que han sido eliminadas (soft deletes)
        $query = FinancialAccount::onlyTrashed(); // Utiliza onlyTrashed() para obtener las eliminadas

        // Filtros de búsqueda
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('type', 'like', '%' . $search . '%');
        }

        if ($request->has('min_balance')) {
            $query->where('balance', '>=', $request->input('min_balance'));
        }

        if ($request->has('max_balance')) {
            $query->where('balance', '<=', $request->input('max_balance'));
        }

        // Paginación con los filtros aplicados
        $financialAccounts = $query->paginate();

        return view('financial-account.trashed', compact('financialAccounts'))
            ->with('i', ($request->input('page', 1) - 1) * $financialAccounts->perPage());
    }

    /**
     * Restore a trashed (soft deleted) financial account.
     */
    public function restore($id): RedirectResponse
    {
        $financialAccount = FinancialAccount::withTrashed()->find($id);
        $financialAccount->restore();

        return Redirect::route('financial-accounts.trashed')
            ->with('success', 'Cuenta restaurada correctamente.');
    }

    /**
     * Permanently delete a trashed (soft deleted) financial account.
     */
    public function forceDelete($id): RedirectResponse
    {
        $financialAccount = FinancialAccount::withTrashed()->find($id);
        $financialAccount->forceDelete();

        return Redirect::route('financial-accounts.trashed')
            ->with('success', 'Cuenta eliminada permanentemente.');
    }
}
