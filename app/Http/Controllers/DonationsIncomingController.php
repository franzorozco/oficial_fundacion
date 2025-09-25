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

use FPDF;


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

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,10,'Historial de Donaciones',0,1,'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,10,'#',1);
        $pdf->Cell(35,10,'Donante Ext.',1);
        $pdf->Cell(35,10,'Usuario Reg.',1);
        $pdf->Cell(35,10,'Decidido por',1);
        $pdf->Cell(20,10,'Estado',1);
        $pdf->Cell(25,10,'Fecha',1);
        $pdf->Cell(50,10,'Notas',1);
        $pdf->Ln();

        $pdf->SetFont('Arial','',9);
        foreach ($donations as $i => $d) {
            $pdf->Cell(10,10,$i+1,1);
            $pdf->Cell(35,10,utf8_decode($d->externalDonor->names ?? '-'),1);
            $pdf->Cell(35,10,utf8_decode($d->user->name ?? '-'),1);
            $pdf->Cell(35,10,utf8_decode($d->receivedBy->name ?? '-'),1);
            $pdf->Cell(20,10,utf8_decode($d->status->name),1);
            $pdf->Cell(25,10,$d->donation_date,1);
            $notes = mb_strimwidth($d->notes, 0, 40, "...");
            $pdf->Cell(50,10,utf8_decode($notes),1);
            $pdf->Ln();
        }

        return response($pdf->Output('S', 'historial_donaciones.pdf'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="historial_donaciones.pdf"');
    }

    public function reconsider($id)
    {
        $donation = Donation::findOrFail($id);

        // Validar que el usuario sea quien tomó la decisión original
        if ($donation->received_by_id != auth()->id()) {
            abort(403);
        }

        // Volver al estado "pendiente" (ajusta el ID según tu DB)
        $donation->status_id = 1;
        $donation->save();

        return back()->with('success', 'La decisión ha sido reconsiderada. La donación está ahora como pendiente.');
    }

    public function generatePdf($id)
    {
        $donation = Donation::with(['user', 'receivedBy', 'externalDonor', 'status', 'campaign', 'items'])->findOrFail($id);
        
        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Donation Details', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Donor: ' . ($donation->externalDonor->names ?? 'N/A'), 0, 1);
        $pdf->Cell(0, 10, 'Received By: ' . ($donation->receivedBy->name ?? 'N/A'), 0, 1);
        $pdf->Cell(0, 10, 'Campaign: ' . ($donation->campaign->name ?? 'N/A'), 0, 1);
        $pdf->Cell(0, 10, 'Donation Date: ' . $donation->donation_date, 0, 1);
        $pdf->Cell(0, 10, 'Status: ' . ($donation->status->name ?? 'N/A'), 0, 1);
        $pdf->Cell(0, 10, 'Notes: ' . ($donation->notes ?? 'N/A'), 0, 1);
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(30, 10, 'Item Name', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Quantity', 1, 0, 'C');
        $pdf->Cell(50, 10, 'Unit', 1, 0, 'C');
        $pdf->Cell(60, 10, 'Description', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 12);
        foreach ($donation->items as $item) {
            $pdf->Cell(30, 10, $item->item_name, 1, 0, 'C');
            $pdf->Cell(30, 10, $item->quantity, 1, 0, 'C');
            $pdf->Cell(50, 10, $item->unit, 1, 0, 'C');
            $pdf->Cell(60, 10, $item->description, 1, 1, 'C');
        }

        $pdf->Output('D', 'donation_details_' . $donation->id . '.pdf');
    }

    public function generateAllDonationsPdf()
    {
        // Obtener todas las donaciones con sus relaciones necesarias
        $donations = Donation::with(['user', 'receivedBy', 'externalDonor', 'status', 'campaign'])->get();
        
        // Crear una instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Título
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'List of All Donations', 0, 1, 'C');
        $pdf->Ln(10);

        // Encabezado de la tabla
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(20, 10, 'No', 1, 0, 'C');
        $pdf->Cell(40, 10, 'External Donor', 1, 0, 'C');
        $pdf->Cell(40, 10, 'User', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Received By', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Status', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Campaign', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Donation Date', 1, 1, 'C');

        // Detalles de las donaciones
        $pdf->SetFont('Arial', '', 12);
        foreach ($donations as $index => $donation) {
            $pdf->Cell(20, 10, ++$index, 1, 0, 'C');
            $pdf->Cell(40, 10, $donation->externalDonor->names ?? '-', 1, 0, 'C');
            $pdf->Cell(40, 10, $donation->user->name ?? '-', 1, 0, 'C');
            $pdf->Cell(40, 10, $donation->receivedBy->name ?? '-', 1, 0, 'C');
            $pdf->Cell(40, 10, $donation->status->name ?? '-', 1, 0, 'C');
            $pdf->Cell(40, 10, $donation->campaign->name ?? '-', 1, 0, 'C');
            $pdf->Cell(40, 10, $donation->donation_date, 1, 1, 'C');
        }

        // Forzar la descarga del PDF
        $pdf->Output('D', 'all_donations.pdf');
    }



}
