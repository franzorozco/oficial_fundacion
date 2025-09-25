<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;
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

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('type', 'like', '%' . $request->search . '%');
            });
        }

        $min = $request->input('min_balance');
        $max = $request->input('max_balance');

        if ($request->filled('min_balance') && is_numeric($request->min_balance)) {
            $query->where('balance', '>=', (float) $request->min_balance);
        }

        if ($request->filled('max_balance') && is_numeric($request->max_balance)) {
            $query->where('balance', '<=', (float) $request->max_balance);
        }



        $financialAccounts = $query->paginate(10)->appends($request->all());

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
        $financialAccount = FinancialAccount::with([
            'transactions' => function($query) {
                $query->orderByDesc('transaction_date');
            },
            'campaignFinances' => function($query) {
                $query->with('campaign')->orderByDesc('created_at');
            }
        ])->findOrFail($id);

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


    public function transfer(Request $request): RedirectResponse
    {
        $request->validate([
            'from_account' => 'required|different:to_account|exists:financial_accounts,id',
            'to_account' => 'required|exists:financial_accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
        ]);

        $fromAccount = FinancialAccount::findOrFail($request->from_account);
        $toAccount = FinancialAccount::findOrFail($request->to_account);
        $amount = $request->amount;

        if ($fromAccount->balance < $amount) {
            return back()->with('error', 'Saldo insuficiente en la cuenta origen.');
        }

        DB::transaction(function () use ($fromAccount, $toAccount, $amount, $request) {
            // Restar a origen
            $fromAccount->balance -= $amount;
            $fromAccount->save();

            // Sumar a destino
            $toAccount->balance += $amount;
            $toAccount->save();

            // Registrar transacción como gasto en cuenta origen
            $now = now();
            Transaction::create([
                'account_id' => $fromAccount->id,
                'type' => 'transferencia-gasto',
                'amount' => $amount,
                'description' => 'Transferencia a: ' . $toAccount->name . '. ' . $request->description,
                'created_by' => auth()->id(),
                'transaction_time' => $now,
            ]);


            // Registrar transacción como ingreso en cuenta destino
            Transaction::create([
                'account_id' => $toAccount->id,
                'type' => 'transferencia-ingreso',
                'amount' => $amount,
                'description' => 'Transferencia desde: ' . $fromAccount->name . '. ' . $request->description,
                'created_by' => auth()->id(),
                'transaction_time' => $now,
            ]);
        });

        return redirect()->route('financial-accounts.index')->with('success', 'Transferencia realizada exitosamente.');
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

    public function generatePDF(Request $request)
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);

        $pdf->Cell(200, 10, 'Reporte de Cuentas Financieras', 0, 1, 'C');
        $pdf->Ln(10);

        // Mostrar filtros aplicados
        if ($request->filled('search') || $request->filled('min_balance') || $request->filled('max_balance')) {
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(50, 10, 'Filtros aplicados:');
            $filters = [];
            if ($request->filled('search')) $filters[] = 'Búsqueda: ' . $request->search;
            if ($request->filled('min_balance')) $filters[] = 'Saldo Mínimo: ' . $request->min_balance;
            if ($request->filled('max_balance')) $filters[] = 'Saldo Máximo: ' . $request->max_balance;
            $pdf->MultiCell(0, 10, implode(', ', $filters));
            $pdf->Ln(5);
        }

        // Encabezado
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 10, 'No', 1);
        $pdf->Cell(60, 10, 'Nombre', 1);
        $pdf->Cell(40, 10, 'Tipo', 1);
        $pdf->Cell(30, 10, 'Saldo', 1);
        $pdf->Cell(50, 10, 'Descripción', 1);
        $pdf->Ln();

        // Aplicar los filtros correctamente
        $query = FinancialAccount::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('type', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('min_balance') && is_numeric($request->min_balance)) {
            $query->where('balance', '>=', (float) $request->min_balance);
        }

        if ($request->filled('max_balance') && is_numeric($request->max_balance)) {
            $query->where('balance', '<=', (float) $request->max_balance);
        }

        $financialAccounts = $query->get();

        // Imprimir filas
        $pdf->SetFont('Arial', '', 10);
        foreach ($financialAccounts as $key => $account) {
            $pdf->Cell(10, 10, $key + 1, 1);
            $pdf->Cell(60, 10, utf8_decode($account->name), 1);
            $pdf->Cell(40, 10, utf8_decode($account->type), 1);
            $pdf->Cell(30, 10, number_format($account->balance, 2), 1);
            $pdf->Cell(50, 10, utf8_decode(Str::limit($account->description, 30)), 1);
            $pdf->Ln();
        }

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="cuentas_financieras.pdf"');
    }

    public function print($id)
    {
        $account = FinancialAccount::with(['transactions', 'campaignFinances.campaign', 'campaignFinances.user'])->findOrFail($id);

        $pdf = new Fpdf('L', 'mm', 'A4'); // Landscape, milímetros, tamaño A4
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Detalles de Cuenta Financiera', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 8, 'Nombre: ' . $account->name, 0, 1);
        $pdf->Cell(0, 8, 'Tipo: ' . $account->type, 0, 1);
        $pdf->Cell(0, 8, 'Balance: $' . number_format($account->balance, 2), 0, 1);
        $pdf->MultiCell(0, 8, 'Descripción: ' . $account->description, 0, 1);

        // Tabla de transacciones
        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Historial de Transacciones', 0, 1);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(220, 220, 220);

        // Encabezados
        $pdf->Cell(40, 8, 'Fecha', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'Tipo', 1, 0, 'C', true);
        $pdf->Cell(35, 8, 'Monto', 1, 0, 'C', true);
        $pdf->Cell(160, 8, 'Descripción', 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 10);
        foreach ($account->transactions as $t) {
            $pdf->Cell(40, 8, $t->transaction_date, 1);
            $pdf->Cell(30, 8, ucfirst($t->type), 1);
            $pdf->Cell(35, 8, '$' . number_format($t->amount, 2), 1);
            $pdf->Cell(160, 8, $t->description, 1);
            $pdf->Ln();
        }

        // Tabla de finanzas por campaña
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Finanzas por Campaña', 0, 1);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(220, 220, 220);

        $pdf->Cell(70, 8, 'Campaña', 1, 0, 'C', true);
        $pdf->Cell(50, 8, 'Manager', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'Ingresos', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'Gastos', 1, 0, 'C', true);
        $pdf->Cell(40, 8, 'Balance Neto', 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 10);
        foreach ($account->campaignFinances as $f) {
            $pdf->Cell(70, 8, optional($f->campaign)->name, 1);
            $pdf->Cell(50, 8, optional($f->user)->name, 1);
            $pdf->Cell(30, 8, '$' . number_format($f->income, 2), 1);
            $pdf->Cell(30, 8, '$' . number_format($f->expenses, 2), 1);
            $pdf->Cell(40, 8, '$' . number_format($f->net_balance, 2), 1);
            $pdf->Ln();
        }

        $pdf->Output('I', 'financial_account_' . $account->id . '.pdf');
        exit;
    }


}
