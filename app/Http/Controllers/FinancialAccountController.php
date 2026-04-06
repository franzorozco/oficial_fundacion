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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;


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
    $query = FinancialAccount::query();

    // Filtros
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('type', 'like', '%' . $request->search . '%');
        });
    }
    if ($request->filled('min_balance') && is_numeric($request->min_balance)) {
        $query->where('balance', '>=', (float)$request->min_balance);
    }
    if ($request->filled('max_balance') && is_numeric($request->max_balance)) {
        $query->where('balance', '<=', (float)$request->max_balance);
    }

    $accounts = $query->get();

    // Datos de auditoría
    $reportData = [
        'date' => now()->format('d/m/Y'),
        'time' => now()->format('H:i'),
        'generated_by' => Auth::user()->name ?? 'Sistema',
        'generated_email' => Auth::user()->email ?? '-',
        'ip' => $request->ip(),
        'user_agent' => $request->header('User-Agent'),
        'total' => $accounts->count(),
        'filters' => [
            'search' => $request->search ?? null,
            'min_balance' => $request->min_balance ?? null,
            'max_balance' => $request->max_balance ?? null,
        ]
    ];

    $pdf = Pdf::loadView('pdf.financial-accounts', compact('accounts', 'reportData'))
              ->setPaper('a4', 'landscape');

    return $pdf->stream('reporte_cuentas_financieras.pdf');
}

public function print($id)
{
    $account = FinancialAccount::with([
        'transactions' => fn($q) => $q->orderByDesc('transaction_date'),
        'campaignFinances.campaign',
        'campaignFinances.user'
    ])->findOrFail($id);

    $reportData = [
        'date' => now()->format('d/m/Y'),
        'time' => now()->format('H:i'),
        'generated_by' => Auth::user()->name ?? 'Sistema',
        'generated_email' => Auth::user()->email ?? '-',
        'ip' => request()->ip(),
        'user_agent' => request()->header('User-Agent'),
    ];

    $pdf = Pdf::loadView('pdf.financial-account-detail', compact('account', 'reportData'))
              ->setPaper('a4', 'landscape');

    return $pdf->stream('detalle_cuenta_' . $account->id . '.pdf');
}


}
