<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\User;
use App\Models\ExternalDonor;
use App\Models\DonationStatus;
use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DonationRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class DonationsIncomingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $filter = $request->input('filter', []);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $orderBy = $request->input('order_by');
        $receivedBy = $request->input('received_by');
        $showInactive = $request->boolean('show_inactive');
        $donations = Donation::with('items')
            ->whereIn('status_id', [1, 2]) // Filtro por estado 1 y 2
            ->withSum('items as total_items', 'quantity')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('receivedBy', fn($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('externalDonor', fn($q) => $q->where('names', 'like', "%{$search}%"))
                        ->orWhereHas('status', fn($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('campaign', fn($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhere('donation_date', 'like', "%{$search}%");
                });
            })
            ->when(!empty($filter), function ($query) use ($filter) {
                $query->where(function ($q) use ($filter) {
                    if (in_array('externo', $filter)) {
                        $q->orWhereNotNull('external_donor_id');
                    }
                    if (in_array('registrado', $filter)) {
                        $q->orWhereNotNull('user_id');
                    }
                });
            })
            ->when($startDate, fn($query) => $query->whereDate('donation_date', '>=', $startDate))
            ->when($endDate, fn($query) => $query->whereDate('donation_date', '<=', $endDate))
            ->when($orderBy === 'month_asc', fn($q) => $q->orderByRaw('MONTH(donation_date) ASC'))
            ->when($orderBy === 'month_desc', fn($q) => $q->orderByRaw('MONTH(donation_date) DESC'))
            ->when($orderBy === 'estado', fn($q) => $q->join('donation_statuses', 'donations.status_id', '=', 'donation_statuses.id')
                                                    ->orderBy('donation_statuses.name'))
            ->when($orderBy === 'items_asc', fn($q) => $q->orderBy('total_items', 'asc'))
            ->when($orderBy === 'items_desc', fn($q) => $q->orderBy('total_items', 'desc'))
            ->when($receivedBy, function ($query, $receivedBy) {
                    $query->whereHas('receivedBy', function ($q) use ($receivedBy) {
                        $q->where('name', 'like', '%' . $receivedBy . '%');
                    });
                })
            ->when(!$showInactive, fn($q) => $q->whereHas('status', fn($q) => $q->where('name', '!=', 'Inactivo')))
            ->when(in_array($orderBy, ['top_users_asc', 'top_users_desc']), function ($q) use ($orderBy) {
                $q->leftJoin('users', 'donations.user_id', '=', 'users.id')
                ->select('donations.*')
                ->selectRaw('COUNT(donations.id) OVER (PARTITION BY donations.user_id) as user_donation_count');

                $direction = $orderBy === 'top_users_asc' ? 'asc' : 'desc';
                $q->orderBy('user_donation_count', $direction);
            })

            ->paginate(10);

        return view('donations-incoming.index', compact('donations'))
            ->with('i', ($request->input('page', 1) - 1) * $donations->perPage());
    }

    public function create(): View
    {
        $donation = new Donation();

        $users = User::select('id', 'name')->get(); // corregido
        $externalDonors = ExternalDonor::select('id', 'names')->get();
        $statuses = DonationStatus::select('id', 'name')->get(); // corregido
        $campaigns = Campaign::select('id', 'name')->get(); // corregido

        // Asumiendo que "received_by" es un usuario, puedes reutilizar $users
        $receivers = $users;

        return view('donations-incoming.create', compact('donation', 'users', 'externalDonors', 'statuses', 'campaigns', 'receivers'));
    }

    public function store(DonationRequest $request): RedirectResponse
    {
        Donation::create($request->validated());

        return back()->with('success', 'Donation created successfully.');
    }

    public function show($id): View
    {
        $donation = Donation::with(['user', 'receivedBy', 'externalDonor', 'status', 'campaign', 'items', 'items.donation_type'])->findOrFail($id);

        return view('donations-incoming.show', compact('donation'));

    }
   
    public function edit($id): View
    {
        $donation = Donation::findOrFail($id);
    
        // Obtener los usuarios, donantes externos, estados y campañas
        $users = User::all();  // Devuelve la colección completa de usuarios
        $externalDonors = ExternalDonor::all();  // Devuelve la colección completa de donantes externos
        $statuses = DonationStatus::all();  // Devuelve la colección completa de estados
        $campaigns = Campaign::all();  // Devuelve la colección completa de campañas
    
        // Si los "receivers" son usuarios, asigna la lista de usuarios a la variable $receivers
        $receivers = $users; // Aquí puedes personalizar la asignación si es necesario

        return view('donations-incoming.edit', compact('donation', 'users', 'externalDonors', 'statuses', 'campaigns', 'receivers'));
    }
    
    public function update(DonationRequest $request, Donation $donation): RedirectResponse
    {
        $donation->update($request->validated());

        return Redirect::route('donations-incoming.index')
            ->with('success', 'Donation updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Donation::findOrFail($id)->delete();

        return back()->with('success', 'Donation deleted successfully');
    }


    // Mostrar donaciones eliminadas
    public function trashed(Request $request): View
    {
        $donations = Donation::onlyTrashed()->with(['user', 'receivedBy', 'externalDonor', 'status', 'campaign'])->paginate();

        return back()->with('i', ($request->input('page', 1) - 1) * $donations->perPage());
    }

    // Restaurar donación
    public function restore($id): RedirectResponse
    {
        $donation = Donation::onlyTrashed()->findOrFail($id);
        $donation->restore();

        return back()->with('success', 'Donación restaurada correctamente.');
    }

    // Eliminar permanentemente                                 
    public function forceDelete($id): RedirectResponse
    {
        $donation = Donation::onlyTrashed()->findOrFail($id);
        $donation->forceDelete();

        return back()->with('success', 'Donación eliminada permanentemente.');
    }

    public function history(Request $request): View
    {
        $query = Donation::with(['items', 'user', 'externalDonor', 'receivedBy', 'campaign', 'status'])
            ->whereIn('status_id', [3, 4]); // Aceptada o Rechazada

        // Filtro por texto general
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhereHas('externalDonor', fn($q) => $q->where('names', 'like', "%{$search}%"))
                ->orWhereHas('receivedBy', fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->whereHas('status', fn($q) => $q->where('name', $request->status));
        }

        // Filtro por usuario que tomó la decisión
        if ($request->filled('decision_by')) {
            $query->where('received_by_id', $request->decision_by);
        }

        $donations = $query->latest('donation_date')->paginate(10);

        // Obtener lista de usuarios para el select
        $users = User::whereHas('receivedDonations')->orderBy('name')->get();

        return view('donations-incoming.history', compact('donations', 'users'));
    }

    public function decisionPdf(Request $request)
    {
        $query = Donation::with(['items', 'user', 'externalDonor', 'receivedBy', 'campaign', 'status'])
            ->whereIn('status_id', [3, 4]);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('externalDonor', fn($q) => $q->where('names', 'like', "%{$search}%"))
                    ->orWhereHas('receivedBy', fn($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('status', fn($q) => $q->where('name', $request->status));
        }

        if ($request->filled('decision_by')) {
            $query->where('received_by_id', $request->decision_by);
        }

        $donations = $query->latest('donation_date')->get();

        $reportData = [
            'generated_by' => Auth::user()->name ?? 'Sistema',
            'generated_email' => Auth::user()->email ?? '-',
            'date' => now()->format('d/m/Y'),
            'time' => now()->format('H:i:s'),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'total' => $donations->count(),
            'filters' => $request->all()
        ];

        $pdf = Pdf::loadView('pdf.donations_history', compact('donations', 'reportData'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('historial_donaciones.pdf');
    }



    public function reconsiderar($id)
    {
        $donation = Donation::findOrFail($id);

        $donation->status_id = 1; // Reconsideración
        $donation->received_by_id = null;

        $donation->save();

        return back()->with('success', 'La donación fue enviada a reconsideración.');
    }

    public function generatePdf(Request $request, $id)
    {
        $donation = Donation::with([
            'user',
            'receivedBy',
            'externalDonor',
            'status',
            'campaign',
            'items'
        ])->findOrFail($id);

        // 📊 Auditoría
        $reportData = [
            'generated_by' => Auth::user()->name ?? 'Sistema',
            'generated_email' => Auth::user()->email ?? '-',
            'date' => now()->format('d/m/Y'),
            'time' => now()->format('H:i:s'),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ];

        $pdf = Pdf::loadView('pdf.donation_detail', compact('donation', 'reportData'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("donacion_{$donation->id}.pdf");
    }

    public function generateAllDonationsPdf(Request $request)
    {
        $donations = Donation::with([
            'user',
            'receivedBy',
            'externalDonor',
            'status',
            'campaign'
        ])->get();

        $reportData = [
            'generated_by' => Auth::user()->name ?? 'Sistema',
            'generated_email' => Auth::user()->email ?? '-',
            'date' => now()->format('d/m/Y'),
            'time' => now()->format('H:i:s'),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'total' => $donations->count(),
        ];

        $pdf = Pdf::loadView('pdf.donations_all', compact('donations', 'reportData'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('todas_las_donaciones.pdf');
    }

}
