<?php

namespace App\Http\Controllers;



use App\Models\DonationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DonationRequestRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade\Pdf;

class DonationRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = DonationRequest::with(['applicantUser', 'userInCharge', 'donation']);

        if ($request->filled('search')) {
            $query->whereHas('applicantUser', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('request_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('request_date', '<=', $request->to_date);
        }

        if ($request->filled('user_in_charge_id')) {
            $query->where('user_in_charge_id', $request->user_in_charge_id);
        }

        $donationRequests = $query->paginate()->appends($request->query());

        $users = User::select('id', 'name')->get();

        return view('donation-request.index', compact('donationRequests', 'users'))
            ->with('i', ($request->input('page', 1) - 1) * $donationRequests->perPage());
    }


    public function create()
    {
        $users = User::all();
        $donations = Donation::all();
        $donationRequest = new DonationRequest();

        return view('donation-request.create', compact('users', 'donations', 'donationRequest'));
    }

    public function store(DonationRequestRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (!empty($data['request_date'])) {
            $data['request_date'] = \Carbon\Carbon::parse($data['request_date'])->format('Y-m-d');
        }

        $donationRequest = DonationRequest::create($data);

        $donationRequest->referencia = 'REF-SOL-' . $donationRequest->id;
        $donationRequest->save();

        return redirect()->to(route('donation-request-descriptions.create', [
            'donation_request_id' => $donationRequest->id
        ]));
    }

    public function show($id): View
    {
        $donationRequest = DonationRequest::with(['applicantUser', 'userInCharge', 'donation', 'description'])->findOrFail($id);

        return view('donation-request.show', compact('donationRequest'));
    }

    public function edit($id): View
    {
        $donationRequest = DonationRequest::find($id);
        $users = User::all();
        $donations = Donation::all();
    
        return view('donation-request.edit', compact('donationRequest', 'users', 'donations'));
    }
    
    public function update(DonationRequestRequest $request, DonationRequest $donationRequest): RedirectResponse
    {
        $donationRequest->update([
            'applicant_user__id' => $request->input('applicant_user__id'),
            'user_in_charge_id' => $request->input('user_in_charge_id'),
            'donation_id' => $request->input('donation_id'),
            'request_date' => $request->input('request_date'),
            'notes' => $request->input('notes'),
            'state' => $request->input('state'),
        ]);
        
        return Redirect::route('donation-requests.index')
            ->with('success', 'DonationRequest updated successfully');
    }
    

    


    public function destroy($id): RedirectResponse
    {
        DonationRequest::find($id)->delete();

        return Redirect::route('donation-requests.index')
            ->with('success', 'DonationRequest deleted successfully');
    }


    public function accept(Request $request, $id)
    {
        $donationRequest = DonationRequest::findOrFail($id);
        $donationRequest->state = 'aceptado';
        $donationRequest->user_in_charge_id = Auth::id();
        $donationRequest->observations = $request->input('observations');
        $donationRequest->save();

        return redirect()->route('donation-requests.show', $id)
            ->with('success', 'La solicitud ha sido aceptada.');
    }

    public function reject(Request $request, $id)
    {
        $donationRequest = DonationRequest::findOrFail($id);
        $donationRequest->state = 'rechazado';
        $donationRequest->user_in_charge_id = Auth::id();
        $donationRequest->observations = $request->input('observations');
        $donationRequest->save();

        return redirect()->route('donation-requests.show', $id)
            ->with('error', 'La solicitud ha sido rechazada.');
    }


public function exportPdf(Request $request)
{
    $query = DonationRequest::with(['applicantUser', 'userInCharge', 'donation']);

    // 🔎 Filtros
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('notes', 'like', '%' . $request->search . '%')
              ->orWhere('state', 'like', '%' . $request->search . '%')
              ->orWhereHas('applicantUser', fn($q2) =>
                    $q2->where('name', 'like', '%' . $request->search . '%')
                )
              ->orWhereHas('userInCharge', fn($q3) =>
                    $q3->where('name', 'like', '%' . $request->search . '%')
                );
        });
    }

    if ($request->filled('state')) {
        $query->where('state', $request->state);
    }

    if ($request->filled('from_date')) {
        $query->whereDate('request_date', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('request_date', '<=', $request->to_date);
    }

    if ($request->filled('user_in_charge_id')) {
        $query->where('user_in_charge_id', $request->user_in_charge_id);
    }

    $donationRequests = $query->get();

    // 📊 Auditoría
    $reportData = [
        'date' => now()->format('d/m/Y'),
        'time' => now()->format('H:i'),
        'generated_by' => Auth::user()->name ?? 'Sistema',
        'generated_email' => Auth::user()->email ?? '-',
        'ip' => $request->ip(),
        'user_agent' => $request->header('User-Agent'),
        'total' => $donationRequests->count(),
    ];

    $pdf = Pdf::loadView('pdf.donation-requests', compact('donationRequests', 'reportData'))
        ->setPaper('a4', 'landscape');

    return $pdf->stream('reporte_solicitudes_donacion.pdf');
}

    public function trashed(Request $request): View
    {
        $donationRequests = DonationRequest::onlyTrashed()->paginate();

        return view('donation-request.trashed', compact('donationRequests'))
            ->with('i', ($request->input('page', 1) - 1) * $donationRequests->perPage());
    }

    public function restore($id): RedirectResponse
    {
        $donationRequest = DonationRequest::withTrashed()->findOrFail($id);
        $donationRequest->restore();

        return Redirect::route('donation-requests.trashed')->with('success', 'Solicitud restaurada exitosamente.');
    }

    public function forceDelete($id): RedirectResponse
    {
        $donationRequest = DonationRequest::withTrashed()->findOrFail($id);
        $donationRequest->forceDelete();

        return Redirect::route('donation-requests.trashed')->with('success', 'Solicitud eliminada permanentemente.');
    }


}
