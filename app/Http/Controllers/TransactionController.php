<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\CampaignFinance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Http\Requests\TransactionRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $description = $request->input('description');
        $createdBy = $request->input('created_by');
        $amountMin = $request->input('amount_min');
        $amountMax = $request->input('amount_max');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $timeFrom = $request->input('time_from');
        $timeTo = $request->input('time_to');

        $query = Transaction::with(['financial_account', 'campaign', 'event', 'event_location', 'user']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('type', 'like', "%{$search}%")
                ->orWhere('amount', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('transaction_date', 'like', "%{$search}%")
                ->orWhere('transaction_time', 'like', "%{$search}%")
                ->orWhereHas('financial_account', fn($q2) => $q2->where('name', 'like', "%{$search}%")->orWhere('id', 'like', "%{$search}%"))
                ->orWhereHas('campaign', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                ->orWhereHas('event', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                ->orWhereHas('event_location', fn($q2) => $q2->where('location_name', 'like', "%{$search}%"))
                ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($description) {
            $query->where('description', 'like', "%{$description}%");
        }

        if ($createdBy) {
            $query->whereHas('user', function ($q) use ($createdBy) {
                $q->where('name', 'like', "%{$createdBy}%");
            });
        }

        if ($amountMin) {
            $query->where('amount', '>=', $amountMin);
        }

        if ($amountMax) {
            $query->where('amount', '<=', $amountMax);
        }

        if ($dateFrom) {
            $query->where('transaction_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('transaction_date', '<=', $dateTo);
        }

        if ($timeFrom) {
            $query->where('transaction_time', '>=', $timeFrom);
        }

        if ($timeTo) {
            $query->where('transaction_time', '<=', $timeTo);
        }

        $transactions = $query->paginate();

        return view('transaction.index', compact('transactions'))
            ->with('i', ($request->input('page', 1) - 1) * $transactions->perPage());
    }

    public function create(): View
    {
        $transaction = new Transaction();

        return view('transaction.create', compact('transaction'));
    }

    public function store(TransactionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['account_id'] = (int) $validated['account_id'];

        if (!isset($validated['account_id']) || !is_numeric($validated['account_id'])) {
            return back()->withErrors(['account_id' => 'La cuenta financiera es obligatoria.']);
        }

        $validated['account_id'] = (int) $validated['account_id'];
        $validated['created_by'] = Auth::id();


        $transaction = Transaction::create($validated);

        if ($transaction->related_campaign_id) {
            $campaignFinance = CampaignFinance::firstOrCreate(
                ['campaign_id' => $transaction->related_campaign_id],
                [
                    'manager_id' => auth()->id(),
                    'income' => 0,
                    'expenses' => 0,
                    'net_balance' => 0,
                ]
            );

            if ($transaction->type === 'ingreso') {
                $campaignFinance->income += $transaction->amount;
            } else {
                $campaignFinance->expenses += $transaction->amount;
            }

            $campaignFinance->net_balance = $campaignFinance->income - $campaignFinance->expenses;
            $campaignFinance->save();
        }


        return back()->with('success', 'Transacción registrada y finanzas actualizadas.');
    }

    public function show($id): View
    {
        $transaction = Transaction::with(['user', 'financial_account', 'campaign', 'event', 'event_location'])
            ->findOrFail($id);

        return view('transaction.show', compact('transaction'));
    }


    public function edit($id): View
    {
        $transaction = Transaction::findOrFail($id);

        return view('transaction.edit', compact('transaction'));
    }

    public function update(TransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $transaction->update($request->validated());

        return Redirect::route('transactions.index')->with('success', 'Transacción actualizada correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return Redirect::route('transactions.index')->with('success', 'Transacción eliminada correctamente.');
    }

    public function downloadPDF($id)
    {
        $transaction = Transaction::with(['financial_account', 'campaign', 'event', 'event_location', 'user'])->findOrFail($id);

        $pdf = Pdf::loadView('transaction.pdf', compact('transaction'))

                ->setPaper('A4', 'portrait');

        return $pdf->stream('comprobante_transaccion_'.$transaction->id.'.pdf');
    }

    public function exportPdf(Request $request)
    {
        $query = Transaction::with(['financial_account', 'campaign', 'event', 'event_location', 'user']);

        // 🔎 Filtros dinámicos igual que en index
        $filters = [
            'search' => $request->input('search'),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
            'created_by' => $request->input('created_by'),
            'amount_min' => $request->input('amount_min'),
            'amount_max' => $request->input('amount_max'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'time_from' => $request->input('time_from'),
            'time_to' => $request->input('time_to'),
        ];

        if ($filters['search']) {
            $query->where(function ($q) use ($filters) {
                $search = $filters['search'];
                $q->where('type', 'like', "%$search%")
                ->orWhere('amount', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%")
                ->orWhere('transaction_date', 'like', "%$search%")
                ->orWhere('transaction_time', 'like', "%$search%")
                ->orWhereHas('financial_account', fn($q2) => $q2->where('name', 'like', "%$search%"))
                ->orWhereHas('campaign', fn($q2) => $q2->where('name', 'like', "%$search%"))
                ->orWhereHas('event', fn($q2) => $q2->where('name', 'like', "%$search%"))
                ->orWhereHas('event_location', fn($q2) => $q2->where('location_name', 'like', "%$search%"))
                ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%$search%"));
            });
        }

        foreach (['type', 'description', 'created_by', 'amount_min', 'amount_max', 'date_from', 'date_to', 'time_from', 'time_to'] as $key) {
            if ($filters[$key]) {
                switch ($key) {
                    case 'created_by':
                        $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$filters[$key]}%"));
                        break;
                    case 'amount_min':
                        $query->where('amount', '>=', $filters[$key]);
                        break;
                    case 'amount_max':
                        $query->where('amount', '<=', $filters[$key]);
                        break;
                    case 'date_from':
                        $query->where('transaction_date', '>=', $filters[$key]);
                        break;
                    case 'date_to':
                        $query->where('transaction_date', '<=', $filters[$key]);
                        break;
                    case 'time_from':
                        $query->where('transaction_time', '>=', $filters[$key]);
                        break;
                    case 'time_to':
                        $query->where('transaction_time', '<=', $filters[$key]);
                        break;
                    default:
                        $query->where($key, 'like', "%{$filters[$key]}%");
                }
            }
        }

        $transactions = $query->get();

        // 🔹 Datos de auditoría
        $reportData = [
            'date' => now()->format('d/m/Y'),
            'time' => now()->format('H:i'),
            'generated_by' => Auth::user()->name ?? 'Sistema',
            'generated_email' => Auth::user()->email ?? '-',
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'total' => $transactions->count(),
        ];

        // 🔹 Generar PDF usando la vista blade
        $pdf = Pdf::loadView('pdf.transactions', compact('transactions', 'reportData'))
                ->setPaper('a4', 'landscape');

        return $pdf->stream('reporte_transacciones.pdf');
    }

}
