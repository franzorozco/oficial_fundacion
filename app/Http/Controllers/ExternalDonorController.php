<?php

namespace App\Http\Controllers;

use App\Models\ExternalDonor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ExternalDonorRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ExternalDonorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $externalDonors = ExternalDonor::paginate();

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
}
