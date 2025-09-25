<?php

namespace App\Http\Controllers;

use App\Models\CampaignFinance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CampaignFinanceRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Campaign; // Asegúrate de tener esta línea al inicio del archivo
use App\Models\User; // Asegúrate de tener esta línea al inicio del archivo
use FPDF;
class CampaignFinanceController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $incomeMin = $request->input('income_min');
        $incomeMax = $request->input('income_max');
        $expensesMin = $request->input('expenses_min');
        $expensesMax = $request->input('expenses_max');
        $balanceMin = $request->input('balance_min');
        $balanceMax = $request->input('balance_max');

        $campaignFinances = CampaignFinance::with(['campaign', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('campaign', fn($q) => $q->where('name', 'LIKE', "%{$search}%"))
                    ->orWhereHas('user', fn($q) => $q->where('name', 'LIKE', "%{$search}%"));
            })
            ->when($incomeMin, fn($q) => $q->where('income', '>=', $incomeMin))
            ->when($incomeMax, fn($q) => $q->where('income', '<=', $incomeMax))
            ->when($expensesMin, fn($q) => $q->where('expenses', '>=', $expensesMin))
            ->when($expensesMax, fn($q) => $q->where('expenses', '<=', $expensesMax))
            ->when($balanceMin, fn($q) => $q->where('net_balance', '>=', $balanceMin))
            ->when($balanceMax, fn($q) => $q->where('net_balance', '<=', $balanceMax))
            ->paginate(10);

        return view('campaign-finance.index', compact('campaignFinances'))
            ->with('i', ($request->input('page', 1) - 1) * $campaignFinances->perPage());
    }


    public function create(): View
    {
        $campaignFinance = new CampaignFinance();
        $campaigns = Campaign::all(); // Obtener todas las campañas
        $users = User::all(); // Obtener todos los usuarios (gerentes)

        return view('campaign-finance.create', compact('campaignFinance', 'campaigns', 'users'));
    }

    public function store(CampaignFinanceRequest $request): RedirectResponse
    {
        CampaignFinance::create($request->validated());

        return Redirect::route('campaign-finances.index')
            ->with('success', 'CampaignFinance created successfully.');
    }

    public function show($id): View
    {
        $campaignFinance = CampaignFinance::find($id);
        $campaignFinance = \App\Models\CampaignFinance::where('campaign_id', $campaign->id)->first();
        $selectedAccountId = $campaignFinance?->financial_account_id;

        return view('campaign-finance.show', compact('campaignFinance', 'selectedAccountId'));
    }

    public function edit($id): View
    {
        $campaignFinance = CampaignFinance::find($id);
        $campaigns = Campaign::all();
        $users = User::all(); 

        return view('campaign-finance.edit', compact('campaignFinance', 'campaigns', 'users'));
    }


    public function update(CampaignFinanceRequest $request, CampaignFinance $campaignFinance): RedirectResponse
    {
        $campaignFinance->update($request->validated());

        return Redirect::route('campaign-finances.index')
            ->with('success', 'CampaignFinance updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        CampaignFinance::find($id)->delete();

        return Redirect::route('campaign-finances.index')
            ->with('success', 'CampaignFinance deleted successfully');
    }

    public function exportPdf(Request $request)
    {
        $query = CampaignFinance::query();
    
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('campaign', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }
    
        $finances = $query->get();
    
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Reporte de Finanzas de Campaña', 0, 1, 'C');
    
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 10, 'No', 1);
        $pdf->Cell(50, 10, 'Campaña', 1);
        $pdf->Cell(40, 10, 'Gerente', 1);
        $pdf->Cell(30, 10, 'Ingreso', 1);
        $pdf->Cell(30, 10, 'Gastos', 1);
        $pdf->Cell(30, 10, 'Balance', 1);
        $pdf->Ln();
    
        $pdf->SetFont('Arial', '', 10);
        foreach ($finances as $i => $f) {
            $pdf->Cell(10, 10, $i + 1, 1);
            $pdf->Cell(50, 10, $f->campaign->name, 1);
            $pdf->Cell(40, 10, $f->user->name, 1);
            $pdf->Cell(30, 10, number_format($f->income, 2), 1);
            $pdf->Cell(30, 10, number_format($f->expenses, 2), 1);
            $pdf->Cell(30, 10, number_format($f->net_balance, 2), 1);
            $pdf->Ln();
        }
    
        $pdf->Output('I', 'reporte_finanzas.pdf');
        exit;
    }

    public function trashed(Request $request): View
    {
        $search = $request->input('search');

        $campaignFinances = CampaignFinance::onlyTrashed() // Obtiene solo los registros eliminados
            ->with(['campaign', 'user'])
            ->when($search, function ($query, $search) {
                $query->whereHas('campaign', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
            })
            ->paginate(10);

        return view('campaign-finance.trashed', compact('campaignFinances'))
            ->with('i', ($request->input('page', 1) - 1) * $campaignFinances->perPage());
    }

    public function restore($id): RedirectResponse
    {
        $campaignFinance = CampaignFinance::onlyTrashed()->findOrFail($id);
        $campaignFinance->restore();

        return Redirect::route('campaign-finances.trashed')
            ->with('success', 'CampaignFinance restored successfully.');
    }

    public function destroyPermanently($id): RedirectResponse
    {
        $campaignFinance = CampaignFinance::onlyTrashed()->findOrFail($id);
        $campaignFinance->forceDelete();

        return Redirect::route('campaign-finances.trashed')
            ->with('success', 'CampaignFinance deleted permanently.');
    }

}
