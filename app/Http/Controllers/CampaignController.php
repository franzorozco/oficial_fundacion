<?php

namespace App\Http\Controllers;
use App\Models\CampaignFinance;
use App\Models\Event;
use App\Models\EventLocation;
use App\Models\FinancialAccount;
use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CampaignRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use FPDF;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use Illuminate\Support\Str;


class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $filters = $request->input('filters', []);
        $minParticipantes = $request->input('min_participantes');
        $maxParticipantes = $request->input('max_participantes');

        $campaigns = \App\Models\Campaign::with(['user', 'events.locations'])
            ->withCount('events')
            ->withCount([
                'events as total_participantes' => function ($query) {
                    $query->join('event_participants', 'events.id', '=', 'event_participants.event_id');
                },
                'events as total_ubicaciones' => function ($query) {
                    $query->join('event_locations', 'events.id', '=', 'event_locations.event_id')
                        ->select(DB::raw('COUNT(DISTINCT event_locations.id)'));
                }

            ])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($query) use ($search) {
                                return $query->where('name', 'like', "%{$search}%");
                            });
            })
            ->when(in_array('activa', $filters), function ($query) {
                return $query->whereDate('end_date', '>=', now());
            })
            ->when(in_array('inactiva', $filters), function ($query) {
                return $query->whereDate('end_date', '<', now());
            })
            ->when(in_array('con_eventos', $filters), function ($query) {
                return $query->has('events');
            })
            ->when(in_array('sin_eventos', $filters), function ($query) {
                return $query->doesntHave('events');
            })
            ->when(in_array('participacion_confirmada', $filters), function ($query) {
                return $query->whereHas('events.participants', function ($q) {
                    $q->where('status', 'asistido');
                });
            })
            ->when(in_array('mayor_eventos', $filters), function ($query) {
                return $query->orderByDesc('events_count');
            })
            ->when(in_array('multi_ubicacion', $filters), function ($query) {
                return $query->whereHas('events', function ($q) {
                    $q->has('locations', '>=', 2);
                });
            })
            ->when(in_array('eventos_largos', $filters), function ($query) {
                return $query->whereHas('events.locations', function ($q) {
                    $q->whereRaw('TIMESTAMPDIFF(MINUTE, start_hour, end_hour) >= 180'); // eventos ≥ 3 horas
                });
            })
            ->when($minParticipantes || $maxParticipantes, function ($query) use ($minParticipantes, $maxParticipantes) {
                $query->whereHas('events', function ($q) use ($minParticipantes, $maxParticipantes) {
                    $q->whereHas('participants', function ($subQ) {
                        // solo necesitamos anidar para tener acceso a participants
                    })
                    ->withCount('participants')
                    ->when($minParticipantes, function ($q) use ($minParticipantes) {
                        $q->having('participants_count', '>=', $minParticipantes);
                    })
                    ->when($maxParticipantes, function ($q) use ($maxParticipantes) {
                        $q->having('participants_count', '<=', $maxParticipantes);
                    });
                });
            })

            ->paginate(10);

        return view('campaign.index', compact('campaigns'))
            ->with('i', ($request->input('page', 1) - 1) * $campaigns->perPage());
    }

    
    public function create(): View
    {
        $campaign = new Campaign();
        $users = User::all();
        $financialAccounts = FinancialAccount::whereNull('deleted_at')->get(); 
        return view('campaign.create', compact('campaign', 'users', 'financialAccounts'));
    }

    public function store(CampaignRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $fundAmount = floatval($request->input('fund_amount', 0));
            $accountId = $request->input('financial_account_id_1');

            if ($accountId) {
                $account = FinancialAccount::whereNull('deleted_at')->findOrFail($accountId);

                if ($fundAmount <= 0) {
                    return back()->withErrors(['fund_amount' => 'El monto debe ser mayor a cero.'])->withInput();
                }

                if ($fundAmount > $account->balance) {
                    return back()->withErrors(['fund_amount' => 'El monto supera el saldo disponible en la cuenta.'])->withInput();
                }
            }

            // Crear campaña sin foto aún
            $campaign = Campaign::create($data);

            // Si hay una imagen, procesarla y guardar nombre en la campaña
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $campaignNameSlug = Str::slug($campaign->name);
                $extension = $file->getClientOriginalExtension();
                $filename = $campaignNameSlug . '-' . time() . '.' . $extension;
                $file->move(public_path('storage/campaigns'), $filename);

                 
                $campaign->foto = $filename;
                $campaign->save();
            }

            // Asignación de fondos si hay cuenta y monto válido
            if ($accountId && $fundAmount > 0) {
                CampaignFinance::create([
                    'campaign_id' => $campaign->id,
                    'manager_id' => $data['creator_id'],
                    'financial_account_id' => $account->id,
                    'income' => $fundAmount,
                    'expenses' => 0,
                    'net_balance' => $fundAmount,
                ]);

                Transaction::create([
                    'account_id' => $account->id,
                    'type' => 'gasto',
                    'amount' => $fundAmount,
                    'description' => 'Asignación inicial de fondos a la campaña ID ' . $campaign->id,
                    'related_campaign_id' => $campaign->id,
                    'transaction_date' => now(),
                    'transaction_time' => now(),
                    'created_by' => auth()->id(),
                ]);

                $account->balance -= $fundAmount;
                $account->save();
            }

            DB::commit();
            return Redirect::route('campaigns.index')->with('success', 'Campaña creada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear campaña: ' . $e->getMessage())->withInput();
        }
    }




    public function show($id): View
    {
        $campaign = Campaign::with('events.eventLocations', 'events.eventParticipants')->findOrFail($id);
        $events = Event::where('campaign_id', $campaign->id)->get();
        $locations = EventLocation::whereIn('event_id', $events->pluck('id'))->get();
        $accounts = FinancialAccount::all();
        $campaignFinance = CampaignFinance::where('campaign_id', $campaign->id)->first();
        $selectedAccountId = $campaignFinance?->financial_account_id;
        return view('campaign.show', compact(
            'campaign',
            'events',
            'locations',
            'accounts',
            'selectedAccountId',
            'campaignFinance'
        ));
    }



    public function edit($id): View
    {
        $campaign = Campaign::find($id);
        $users = User::all();
        $financialAccounts = FinancialAccount::whereNull('deleted_at')->get(); // Solo activas
        return view('campaign.edit', compact('campaign', 'users', 'financialAccounts'));
    }

    public function update(CampaignRequest $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            // Validar y procesar cuenta y monto si se proporciona
            $accountId = $request->input('financial_account_id_1');
            $fundAmount = floatval($request->input('fund_amount', 0));
            $account = null;

            if ($accountId) {
                $account = FinancialAccount::whereNull('deleted_at')->findOrFail($accountId);

                if ($fundAmount < 0) {
                    return back()->withErrors(['fund_amount' => 'El monto no puede ser negativo.'])->withInput();
                }

                if ($fundAmount > $account->balance) {
                    return back()->withErrors(['fund_amount' => 'El monto supera el saldo disponible en la cuenta.'])->withInput();
                }
            }

            // Actualizar datos de la campaña
            $campaign->update($data);

            // Procesar imagen si se carga una nueva
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $campaignNameSlug = Str::slug($campaign->name);
                $extension = $file->getClientOriginalExtension();
                $filename = $campaignNameSlug . '-' . time() . '.' . $extension;
                $file->move(public_path('storage/campaigns'), $filename);

                $campaign->foto = $filename;
                $campaign->save();
            }

            // Manejar asignación financiera
            if ($accountId) {
                $existingFinance = CampaignFinance::where('campaign_id', $campaign->id)->first();

                if ($existingFinance) {
                    $existingFinance->update([
                        'manager_id' => $data['creator_id'],
                        'financial_account_id' => $account->id,
                        // no actualizamos income/gastos porque ya existe
                    ]);
                } else {
                    // Crear nuevo registro financiero y transacción si se asigna monto
                    CampaignFinance::create([
                        'campaign_id' => $campaign->id,
                        'manager_id' => $data['creator_id'],
                        'financial_account_id' => $account->id,
                        'income' => $fundAmount,
                        'expenses' => 0,
                        'net_balance' => $fundAmount,
                    ]);

                    if ($fundAmount > 0) {
                        Transaction::create([
                            'account_id' => $account->id,
                            'type' => 'gasto',
                            'amount' => $fundAmount,
                            'description' => 'Asignación inicial de fondos a la campaña ID ' . $campaign->id,
                            'related_campaign_id' => $campaign->id,
                            'transaction_date' => now(),
                            'transaction_time' => now(),
                            'created_by' => auth()->id(),
                        ]);

                        $account->balance -= $fundAmount;
                        $account->save();
                    }
                }
            }

            DB::commit();

            return Redirect::route('campaigns.index')->with('success', 'Campaña actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la campaña: ' . $e->getMessage())->withInput();
        }
    }



    public function destroy($id): RedirectResponse
    {
        Campaign::find($id)->delete();

        return Redirect::route('campaigns.index')
            ->with('success', 'Campaign deleted successfully');
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







    // PARA PDF 
     public function generatePdf(Request $request)
    {
        $search = $request->input('search');
        $filters = $request->input('filters', []);
        $minParticipantes = $request->input('min_participantes');
        $maxParticipantes = $request->input('max_participantes');

        $campaigns = Campaign::with(['user', 'events.locations', 'events.participants'])
            ->withCount('events')
            ->withCount([
                'events as total_participantes' => function ($query) {
                    $query->join('event_participants', 'events.id', '=', 'event_participants.event_id');
                },
                'events as total_ubicaciones' => function ($query) {
                    $query->join('event_locations', 'events.id', '=', 'event_locations.event_id')
                        ->select(DB::raw('COUNT(DISTINCT event_locations.id)'));
                }
            ])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            })
            ->when(in_array('activa', $filters), fn($q) => $q->whereDate('end_date', '>=', now()))
            ->when(in_array('inactiva', $filters), fn($q) => $q->whereDate('end_date', '<', now()))
            ->when(in_array('con_eventos', $filters), fn($q) => $q->has('events'))
            ->when(in_array('sin_eventos', $filters), fn($q) => $q->doesntHave('events'))
            ->when($minParticipantes !== null, fn($q) => $q->having('total_participantes', '>=', $minParticipantes))
            ->when($maxParticipantes !== null, fn($q) => $q->having('total_participantes', '<=', $maxParticipantes))
            ->when(in_array('mayor_eventos', $filters), fn($q) => $q->orderByDesc('events_count'))
            ->when(in_array('multi_ubicacion', $filters), fn($q) => $q->having('total_ubicaciones', '>', 1))
            ->when(in_array('eventos_largos', $filters), function ($query) {
                $query->whereHas('events.locations', function ($q) {
                    $q->whereRaw('TIMESTAMPDIFF(MINUTE, start_hour, end_hour) >= 180');
                });
            })
            ->get();

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Lista de Campañas', 0, 1, 'C');
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(8, 8, 'No', 1, 0, 'C', true);
        $pdf->Cell(28, 8, 'Creador', 1, 0, 'C', true);
        $pdf->Cell(32, 8, 'Nombre Campaña', 1, 0, 'C', true);
        $pdf->Cell(18, 8, 'Eventos', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'N° Ubics.', 1, 0, 'C', true);
        $pdf->Cell(45, 8, 'Ubicaciones', 1, 0, 'C', true);
        $pdf->Cell(22, 8, 'Participantes', 1, 0, 'C', true);
        $pdf->Cell(50, 8, 'Descripción', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Inicio', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Fin', 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 7);

        foreach ($campaigns as $index => $campaign) {
            $ubicaciones = [];
            foreach ($campaign->events as $event) {
                foreach ($event->locations as $location) {
                    $ubicaciones[] = $location->location_name;
                }
            }
            $ubicaciones = implode(', ', array_unique($ubicaciones));
            $ubicaciones = substr($ubicaciones, 0, 45);

            $pdf->Cell(8, 8, $index + 1, 1);
            $pdf->Cell(28, 8, substr($campaign->user->name ?? '-', 0, 28), 1);
            $pdf->Cell(32, 8, substr($campaign->name, 0, 32), 1);
            $pdf->Cell(18, 8, $campaign->events_count, 1);
            $pdf->Cell(20, 8, $campaign->total_ubicaciones, 1);
            $pdf->Cell(45, 8, $ubicaciones ?: '-', 1);
            $pdf->Cell(22, 8, $campaign->total_participantes ?? '0', 1);
            $pdf->Cell(50, 8, substr($campaign->description, 0, 50), 1);
            $pdf->Cell(20, 8, $campaign->start_date ?? '-', 1);
            $pdf->Cell(20, 8, $campaign->end_date ?? '-', 1);
            $pdf->Ln();
        }

        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="campaigns.pdf"');
    }


    public function generateReport($id)
    {
        $campaign = Campaign::with([
            'user',
            'events.user',
            'events.eventLocations',
            'events.eventParticipants.user',
            'events.eventParticipants.eventLocation'
        ])->findOrFail($id);

        $pdf = new Fpdf();
        $pdf->AddPage();
        
        // Encabezado
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('FUNDACIÓN UNIFRANZ'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, utf8_decode('Recibo Oficial de Actividad Comunitaria'), 0, 1, 'C');
        $pdf->Ln(5);

        // Información general
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Fecha de Emisión: ' . date('d/m/Y'), 0, 1);
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'DATOS DE LA CAMPAÑA', 0, 1);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, 'Nombre: ' . $campaign->name, 0, 1);
        $pdf->Cell(0, 8, 'Responsable: ' . ($campaign->user->name ?? 'N/A'), 0, 1);
        $pdf->MultiCell(0, 8, 'Descripción: ' . $campaign->description, 0, 1);
        $pdf->Cell(0, 8, 'Inicio: ' . $campaign->start_date, 0, 1);
        $pdf->Cell(0, 8, 'Finalización: ' . $campaign->end_date, 0, 1);
        $pdf->Ln(5);

        if ($campaign->events->count()) {
            foreach ($campaign->events as $event) {
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, 'Evento: ' . $event->name, 0, 1);
                $pdf->SetFont('Arial', '', 11);
                $pdf->MultiCell(0, 7, 'Descripción: ' . ($event->description ?? 'Sin descripción'), 0, 1);
                $pdf->Cell(0, 7, 'Fecha: ' . $event->event_date, 0, 1);
                $pdf->Cell(0, 7, 'Organizador: ' . ($event->user->name ?? 'N/A'), 0, 1);

                // Ubicaciones
                if ($event->eventLocations->count()) {
                    $pdf->SetFont('Arial', 'I', 11);
                    $pdf->Cell(0, 7, 'Ubicaciones del evento:', 0, 1);
                    foreach ($event->eventLocations as $loc) {
                        $pdf->SetFont('Arial', '', 11);
                        $pdf->MultiCell(0, 6, '- ' . $loc->location_name . ' (' . ($loc->address ?? 'Dirección no disponible') . ')', 0, 1);
                    }
                } else {
                    $pdf->Cell(0, 7, 'Ubicaciones: No registradas', 0, 1);
                }

                // Participantes
                if ($event->eventParticipants->count()) {
                    $pdf->SetFont('Arial', 'I', 11);
                    $pdf->Cell(0, 7, 'Participantes:', 0, 1);
                    foreach ($event->eventParticipants as $participant) {
                        $user = $participant->user;
                        $location = $participant->eventLocation;

                        $pdf->SetFont('Arial', '', 11);
                        $pdf->Cell(0, 6, '- Nombre: ' . ($user->name ?? 'Sin nombre'), 0, 1);
                        $pdf->Cell(0, 6, '  Correo: ' . ($user->email ?? 'No disponible'), 0, 1);
                        $pdf->Cell(0, 6, '  Edad: ' . ($user->edad ?? 'No registrada'), 0, 1);
                        $pdf->Cell(0, 6, '  Estado: ' . $participant->status, 0, 1);
                        if ($location) {
                            $pdf->Cell(0, 6, '  Ubicación asignada: ' . $location->location_name, 0, 1);
                        }
                        $pdf->Ln(2);
                    }
                } else {
                    $pdf->Cell(0, 7, 'Participantes: No registrados', 0, 1);
                }

                $pdf->Ln(5);
            }
        } else {
            $pdf->Cell(0, 8, 'No se han registrado eventos en esta campaña.', 0, 1);
        }

        // Pie de recibo
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 6, utf8_decode('Este recibo certifica la realización de las actividades anteriormente descritas.'), 0, 1, 'C');
        $pdf->Ln(15);
        $pdf->Cell(0, 6, '___________________________', 0, 1, 'C');
        $pdf->Cell(0, 6, 'Fundación UNIFRANZ', 0, 1, 'C');
        $pdf->Cell(0, 6, 'La Paz - Bolivia', 0, 1, 'C');

        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="recibo_unifranz.pdf"');
    }
}
