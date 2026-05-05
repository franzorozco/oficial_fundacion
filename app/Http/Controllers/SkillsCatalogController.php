<?php

namespace App\Http\Controllers;

use App\Models\SkillsCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\SkillsCatalogRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SkillsCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $skillsCatalogs = SkillsCatalog::paginate();

        return view('skills-catalog.index', compact('skillsCatalogs'))
            ->with('i', ($request->input('page', 1) - 1) * $skillsCatalogs->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $skillsCatalog = new SkillsCatalog();

        return view('skills-catalog.create', compact('skillsCatalog'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SkillsCatalogRequest $request): RedirectResponse
    {
        SkillsCatalog::create($request->validated());

        return Redirect::route('skills-catalogs.index')
            ->with('success', 'SkillsCatalog created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $skillsCatalog = SkillsCatalog::find($id);

        return view('skills-catalog.show', compact('skillsCatalog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $skillsCatalog = SkillsCatalog::find($id);

        return view('skills-catalog.edit', compact('skillsCatalog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SkillsCatalogRequest $request, SkillsCatalog $skillsCatalog): RedirectResponse
    {
        $skillsCatalog->update($request->validated());

        return Redirect::route('skills-catalogs.index')
            ->with('success', 'SkillsCatalog updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        SkillsCatalog::find($id)->delete();

        return Redirect::route('skills-catalogs.index')
            ->with('success', 'SkillsCatalog deleted successfully');
    }
}
