<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\FinancialAccountRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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
            ->with('success', 'FinancialAccount created successfully.');
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
            ->with('success', 'FinancialAccount updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        FinancialAccount::find($id)->delete();

        return Redirect::route('financial-accounts.index')
            ->with('success', 'FinancialAccount deleted successfully');
    }
}
