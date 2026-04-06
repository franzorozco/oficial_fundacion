<?php

namespace App\Http\Controllers;

use App\Models\VolunteerVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\VolunteerVerificationRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;


class VolunteerVerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = VolunteerVerification::whereIn('status', ['pendiente', 'reconsideracion']);

        // Filtrado por los parámetros de búsqueda
        if ($request->has('search') && $request->input('search') !== '') {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                })
                ->orWhere('document_type', 'like', '%' . $searchTerm . '%')
                ->orWhere('name_document', 'like', '%' . $searchTerm . '%');
            });
        }

        $volunteerVerifications = $query->paginate();

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

    public function approve(Request $request, $id): RedirectResponse
    {
        $request->validate(['coment' => 'required|string|max:255']);

        $verification = VolunteerVerification::findOrFail($id);
        $verification->status = 'aprobado';
        $verification->user_resp_id = Auth::id();
        $verification->coment = $request->coment;
        $verification->save();

        // Asignar el rol de "Voluntario" al usuario verificado
        $user = $verification->user;
        if ($user && !$user->hasRole('Voluntario')) {
            $user->assignRole('Voluntario');
        }

        return redirect()->route('volunteer-verifications.index')->with('success', 'Solicitud aprobada y rol asignado.');
    }

    // Rechazar
    public function reject(Request $request, $id): RedirectResponse
    {
        $request->validate(['coment' => 'required|string|max:255']);

        $verification = VolunteerVerification::findOrFail($id);
        $verification->status = 'rechazado';
        $verification->user_resp_id = Auth::id();
        $verification->coment = $request->coment;
        $verification->save();

        // Remover el rol "Voluntario" si lo tiene
        $user = $verification->user;
        if ($user && $user->hasRole('Voluntario')) {
            $user->removeRole('Voluntario');
        }

        return redirect()->route('volunteer-verifications.index')->with('success', 'Solicitud rechazada y rol removido si era necesario.');
    }

    public function misDecisiones(Request $request)
    {
        $search = $request->input('search');

        $query = VolunteerVerification::with(['user', 'userResp'])
            ->where('user_resp_id', auth()->id())
            ->whereIn('status', ['Aprobado', 'Rechazado']);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'LIKE', "%$search%");
                })->orWhere('name_document', 'LIKE', "%$search%");
            });
        }
        $volunteerVerifications = $query->paginate(10);

        return view('volunteer-verification.mis-decisiones', compact('volunteerVerifications'))
                ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function reconsiderar($id)
    {
        $verification = VolunteerVerification::findOrFail($id);

        // Solo permitir si el estado actual es aprobado o rechazado
        if (in_array($verification->status, ['aprobado', 'rechazado'])) {
            $verification->status = 'reconsideracion';
            $verification->save();

            return redirect()->back()->with('success', 'La verificación ha sido marcada como "en reconsideración".');
        }

        return redirect()->back()->with('error', 'No se puede reconsiderar este estado.');
    }



public function generatePdf(Request $request)
{
    $query = VolunteerVerification::with(['user', 'userResp']);

    if ($request->filled('search')) {
        $searchTerm = $request->search;

        $query->where(function ($q) use ($searchTerm) {
            $q->whereHas('user', function ($q2) use ($searchTerm) {
                $q2->where('name', 'like', '%' . $searchTerm . '%');
            })
            ->orWhere('document_type', 'like', '%' . $searchTerm . '%')
            ->orWhere('name_document', 'like', '%' . $searchTerm . '%');
        });
    }

    $volunteerVerifications = $query->latest()->get();

    // 🔥 AUDITORÍA (MISMO FORMATO)
    $reportData = [
        'generated_by' => auth()->user()->name ?? 'Sistema',
        'generated_email' => auth()->user()->email ?? '-',
        'date' => now()->format('d/m/Y'),
        'time' => now()->format('H:i:s'),
        'ip' => $request->ip(),
        'user_agent' => $request->header('User-Agent'),
        'total' => $volunteerVerifications->count(),
        'filters' => $request->all()
    ];

    $pdf = Pdf::loadView(
        'pdf.volunteer_verifications_report',
        compact('volunteerVerifications', 'reportData')
    )->setPaper('a4', 'landscape');

    return $pdf->stream('reporte_verificaciones_voluntarios.pdf');
}
    
public function trashed(Request $request): View
{
    $volunteerVerifications = VolunteerVerification::onlyTrashed()->paginate();
    return view('volunteer-verification.trashed', compact('volunteerVerifications'))
        ->with('i', ($request->input('page', 1) - 1) * $volunteerVerifications->perPage());
}

public function restore($id): RedirectResponse
{
    $verification = VolunteerVerification::onlyTrashed()->findOrFail($id);
    $verification->restore();

    return redirect()->route('volunteer-verifications.trashed')
        ->with('success', 'Verificación restaurada correctamente.');
}

public function forceDelete($id): RedirectResponse
{
    $verification = VolunteerVerification::onlyTrashed()->findOrFail($id);
    $verification->forceDelete();

    return redirect()->route('volunteer-verifications.trashed')
        ->with('success', 'Verificación eliminada permanentemente.');
}


}
