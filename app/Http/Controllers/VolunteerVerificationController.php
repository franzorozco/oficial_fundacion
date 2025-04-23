<?php

namespace App\Http\Controllers;

use App\Models\VolunteerVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\VolunteerVerificationRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class VolunteerVerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $volunteerVerifications = VolunteerVerification::paginate();

        return view('volunteer-verification.index', compact('volunteerVerifications'))
            ->with('i', ($request->input('page', 1) - 1) * $volunteerVerifications->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $volunteerVerification = new VolunteerVerification();

        return view('volunteer-verification.create', compact('volunteerVerification'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VolunteerVerificationRequest $request): RedirectResponse
    {
        VolunteerVerification::create($request->validated());

        return Redirect::route('volunteer-verifications.index')
            ->with('success', 'VolunteerVerification created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $volunteerVerification = VolunteerVerification::find($id);

        return view('volunteer-verification.show', compact('volunteerVerification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $volunteerVerification = VolunteerVerification::find($id);

        return view('volunteer-verification.edit', compact('volunteerVerification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VolunteerVerificationRequest $request, VolunteerVerification $volunteerVerification): RedirectResponse
    {
        $volunteerVerification->update($request->validated());

        return Redirect::route('volunteer-verifications.index')
            ->with('success', 'VolunteerVerification updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        VolunteerVerification::find($id)->delete();

        return Redirect::route('volunteer-verifications.index')
            ->with('success', 'VolunteerVerification deleted successfully');
    }
}
