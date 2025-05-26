<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CampaignRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use FPDF;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search'); // Obtener el término de búsqueda
    
        // Filtrar las campañas si existe un término de búsqueda
        $campaigns = Campaign::with('user')
            ->withCount('events') // Cuenta directa de eventos
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%")
                             ->orWhereHas('user', function ($query) use ($search) {
                                 return $query->where('name', 'like', "%{$search}%");
                             });
            })
            ->paginate(10);
    
        return view('campaign.index', compact('campaigns'))
            ->with('i', ($request->input('page', 1) - 1) * $campaigns->perPage());
    }
    

    

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $campaign = new Campaign();
        $users = User::all(); // Para usar en un select

        return view('campaign.create', compact('campaign', 'users'));
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
        $campaign = Campaign::with('events.eventLocations', 'events.eventParticipants') // Cargar las relaciones necesarias
            ->find($id);

        return view('campaign.show', compact('campaign'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $campaign = Campaign::find($id);
        $users = User::all();

        return view('campaign.edit', compact('campaign', 'users'));
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
    
    
    public function generatePdf (Request $request)
    {
        $search = $request->input('search'); // Obtener el término de búsqueda

        // Filtrar las campañas si existe un término de búsqueda
        $campaigns = Campaign::with('user')
            ->withCount('events') // Cuenta directa de eventos
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($query) use ($search) {
                                return $query->where('name', 'like', "%{$search}%");
                            });
            })
            ->get();

        // Crear una instancia de FPDF
        $pdf = new FPDF('L'); // 'L' indica orientación horizontal
        $pdf->AddPage();

        // Título
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'List of Campaigns', 0, 1, 'C');
        $pdf->Ln(10);

        // Encabezado de la tabla
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(20, 10, 'No', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Creator', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Name', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Events', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Description', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Start Date', 1, 0, 'C');
        $pdf->Cell(30, 10, 'End Date', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Start Hour', 1, 0, 'C');
        $pdf->Cell(30, 10, 'End Hour', 1, 1, 'C');

        // Detalles de las campañas
        $pdf->SetFont('Arial', '', 12);
        foreach ($campaigns as $index => $campaign) {
            $pdf->Cell(20, 10, ++$index, 1, 0, 'C');
            $pdf->Cell(40, 10, $campaign->user->name ?? '-', 1, 0, 'C');
            $pdf->Cell(40, 10, $campaign->name, 1, 0, 'C');
            $pdf->Cell(30, 10, $campaign->events_count, 1, 0, 'C');
            $pdf->Cell(40, 10, $campaign->description, 1, 0, 'C');
            $pdf->Cell(30, 10, $campaign->start_date, 1, 0, 'C');
            $pdf->Cell(30, 10, $campaign->end_date, 1, 0, 'C');
            $pdf->Cell(30, 10, $campaign->start_hour, 1, 0, 'C');
            $pdf->Cell(30, 10, $campaign->end_hour, 1, 1, 'C');
        }

        // Forzar la descarga del PDF
        $pdf->Output('D', 'filtered_campaigns.pdf');
    }
    public function trashed(Request $request): View
    {
        $search = $request->input('search');

        $campaigns = Campaign::onlyTrashed()
            ->with('user')
            ->withCount('events')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($query) use ($search) {
                                return $query->where('name', 'like', "%{$search}%");
                            });
            })
            ->paginate(10);

        return view('campaign.trashed', compact('campaigns'))
            ->with('i', ($request->input('page', 1) - 1) * $campaigns->perPage());
    }

        public function restore($id): RedirectResponse
    {
        $campaign = Campaign::onlyTrashed()->findOrFail($id);
        $campaign->restore();

        return redirect()->route('campaigns.trashed')->with('success', 'Campaign restored successfully.');
    }

    public function forceDelete($id): RedirectResponse
    {
        $campaign = Campaign::onlyTrashed()->findOrFail($id);
        $campaign->forceDelete();

        return redirect()->route('campaigns.trashed')->with('success', 'Campaign permanently deleted.');
    }



}
