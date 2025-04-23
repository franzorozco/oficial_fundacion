<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CampaignRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $campaigns = Campaign::paginate();

        return view('campaign.index', compact('campaigns'))
            ->with('i', ($request->input('page', 1) - 1) * $campaigns->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $campaign = new Campaign();

        return view('campaign.create', compact('campaign'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CampaignRequest $request): RedirectResponse
    {
        Campaign::create($request->validated());

        return Redirect::route('campaigns.index')
            ->with('success', 'Campaign created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $campaign = Campaign::find($id);

        return view('campaign.show', compact('campaign'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $campaign = Campaign::find($id);

        return view('campaign.edit', compact('campaign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CampaignRequest $request, Campaign $campaign): RedirectResponse
    {
        $campaign->update($request->validated());

        return Redirect::route('campaigns.index')
            ->with('success', 'Campaign updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Campaign::find($id)->delete();

        return Redirect::route('campaigns.index')
            ->with('success', 'Campaign deleted successfully');
    }
}
