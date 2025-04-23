<?php

namespace App\Http\Controllers;

use App\Models\CampaignFinance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CampaignFinanceRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CampaignFinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $campaignFinances = CampaignFinance::paginate();

        return view('campaign-finance.index', compact('campaignFinances'))
            ->with('i', ($request->input('page', 1) - 1) * $campaignFinances->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $campaignFinance = new CampaignFinance();

        return view('campaign-finance.create', compact('campaignFinance'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CampaignFinanceRequest $request): RedirectResponse
    {
        CampaignFinance::create($request->validated());

        return Redirect::route('campaign-finances.index')
            ->with('success', 'CampaignFinance created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $campaignFinance = CampaignFinance::find($id);

        return view('campaign-finance.show', compact('campaignFinance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $campaignFinance = CampaignFinance::find($id);

        return view('campaign-finance.edit', compact('campaignFinance'));
    }

    /**
     * Update the specified resource in storage.
     */
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
}
