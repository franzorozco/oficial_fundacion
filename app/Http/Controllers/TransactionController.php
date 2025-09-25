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
use FPDF;

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

        return $pdf->download('comprobante_transaccion_'.$transaction->id.'.pdf');
    }


    public function exportPdf(Request $request)
    {
        // Filtros igual que en el index
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
                ->orWhereHas('financial_account', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                ->orWhereHas('campaign', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                ->orWhereHas('event', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                ->orWhereHas('event_location', fn($q2) => $q2->where('location_name', 'like', "%{$search}%"))
                ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        if ($type) $query->where('type', $type);
        if ($description) $query->where('description', 'like', "%{$description}%");
        if ($createdBy) {
            $query->whereHas('user', function ($q) use ($createdBy) {
                $q->where('name', 'like', "%{$createdBy}%");
            });
        }
        if ($amountMin) $query->where('amount', '>=', $amountMin);
        if ($amountMax) $query->where('amount', '<=', $amountMax);
        if ($dateFrom) $query->where('transaction_date', '>=', $dateFrom);
        if ($dateTo) $query->where('transaction_date', '<=', $dateTo);
        if ($timeFrom) $query->where('transaction_time', '>=', $timeFrom);
        if ($timeTo) $query->where('transaction_time', '<=', $timeTo);

        $transactions = $query->get();

        // Crear PDF horizontal
        $pdf = new Fpdf('L', 'mm', 'A4'); // 'L' = Landscape
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 9);

        // Títulos
        $pdf->Cell(10, 10, '#', 1);
        $pdf->Cell(30, 10, 'Cuenta (ID)', 1);
        $pdf->Cell(20, 10, 'Tipo', 1);
        $pdf->Cell(25, 10, 'Monto', 1);
        $pdf->Cell(40, 10, 'Descripción', 1);
        $pdf->Cell(30, 10, 'Campaña', 1);
        $pdf->Cell(30, 10, 'Evento', 1);
        $pdf->Cell(35, 10, 'Ubicación Evento', 1);
        $pdf->Cell(25, 10, 'Fecha', 1);
        $pdf->Cell(20, 10, 'Hora', 1);
        $pdf->Cell(30, 10, 'Creado por', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);

        foreach ($transactions as $i => $t) {
            $pdf->Cell(10, 8, $i + 1, 1);
            $pdf->Cell(30, 8, ($t->financial_account->name ?? 'N/A') . ' (' . ($t->financial_account->id ?? 'N/A') . ')', 1);
            $pdf->Cell(20, 8, ucfirst($t->type), 1);
            $pdf->Cell(25, 8, number_format($t->amount, 2), 1);
            $pdf->Cell(40, 8, substr($t->description, 0, 35), 1);
            $pdf->Cell(30, 8, $t->campaign->name ?? 'N/A', 1);
            $pdf->Cell(30, 8, $t->event->name ?? 'N/A', 1);
            $pdf->Cell(35, 8, $t->event_location->location_name ?? 'N/A', 1);
            $pdf->Cell(25, 8, $t->transaction_date->format('Y/m/d'), 1);
            $pdf->Cell(20, 8, $t->transaction_time->format('H:i'), 1);
            $pdf->Cell(30, 8, $t->user->name ?? 'N/A', 1);
            $pdf->Ln();
        }

        $pdf->Output('D', 'transactions.pdf');
        exit;
    }


}
