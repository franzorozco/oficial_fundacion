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
use FPDF;

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
        $query = VolunteerVerification::query();
    
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
    
        $volunteerVerifications = $query->get();
    
        // Crear una nueva instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
    
        // Agregar un título
        $pdf->Cell(200, 10, 'Volunteer Verifications Report', 0, 1, 'C');
    
        // Agregar los encabezados de la tabla
        $pdf->Cell(20, 10, 'No', 1);
        $pdf->Cell(40, 10, 'User Name', 1);
        $pdf->Cell(40, 10, 'User Resp Name', 1);
        $pdf->Cell(40, 10, 'Document Type', 1);
        $pdf->Cell(40, 10, 'Document Url', 1);
        $pdf->Cell(40, 10, 'Name Document', 1);
        $pdf->Cell(30, 10, 'Status', 1);
        $pdf->Cell(50, 10, 'Comment', 1);
        $pdf->Ln();
    
        // Agregar los datos de cada verificación de voluntario
        foreach ($volunteerVerifications as $index => $verification) {
            $pdf->Cell(20, 10, $index + 1, 1);
            $pdf->Cell(40, 10, $verification->user ? $verification->user->name : 'N/A', 1);
            $pdf->Cell(40, 10, $verification->userResp ? $verification->userResp->name : 'N/A', 1);
            $pdf->Cell(40, 10, $verification->document_type, 1);
            $pdf->Cell(40, 10, $verification->document_url, 1);
            $pdf->Cell(40, 10, $verification->name_document, 1);
            $pdf->Cell(30, 10, $verification->status, 1);
            $pdf->Cell(50, 10, $verification->coment, 1);
            $pdf->Ln();
        }
    
        // Generar el archivo PDF y devolverlo como respuesta de flujo
        return response()->stream(function() use ($pdf) {
            $pdf->Output('I', 'volunteer_verifications_report.pdf');  // 'I' para mostrar en el navegador
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="volunteer_verifications_report.pdf"'
        ]);
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
