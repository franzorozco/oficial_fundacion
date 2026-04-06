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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
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


public function search(Request $request)
{
    $query = Campaign::with(['user', 'events']);

    $search = $request->input('q') ?? $request->input('search');

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('user', function ($u) use ($search) {
                  $u->where('name', 'like', "%{$search}%");
              });
        });
    }

    return response()->json(
        $query->latest()->limit(20)->get()
    );
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
        ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
        ->get();

    // 🔥 AUDITORÍA
    $reportData = [
        'generated_by' => Auth::check() ? Auth::user()->name : 'Sistema',
        'generated_email' => Auth::check() ? Auth::user()->email : '-',
        'date' => now()->format('d/m/Y'),
        'time' => now()->format('H:i:s'),
        'ip' => $request->ip(),
        'user_agent' => $request->header('User-Agent'),
        'total' => $campaigns->count(),

        'filters' => [
            'search' => $search,
            'filters' => $filters,
            'min_participantes' => $minParticipantes,
            'max_participantes' => $maxParticipantes,
        ]
    ];

    $pdf = Pdf::loadView('pdf.campaigns', compact('campaigns', 'reportData'))
        ->setPaper('a4', 'landscape');

    return $pdf->stream('reporte_campañas.pdf');
}


public function generateReport($id, Request $request)
{
    $campaign = Campaign::with([
        'user',
        'events.user',
        'events.eventLocations',
        'events.eventParticipants.user',
        'events.eventParticipants.eventLocation'
    ])->findOrFail($id);

    // 🔥 AUDITORÍA
    $reportData = [
        'generated_by' => Auth::check() ? Auth::user()->name : 'Sistema',
        'generated_email' => Auth::check() ? Auth::user()->email : '-',
        'date' => now()->format('d/m/Y'),
        'time' => now()->format('H:i:s'),
        'ip' => $request->ip(),
        'user_agent' => $request->header('User-Agent'),
        'campaign_id' => $campaign->id,
    ];

    $pdf = Pdf::loadView('pdf.campaign_detail', compact('campaign', 'reportData'))
        ->setPaper('a4', 'portrait');

    return $pdf->stream("campaña_{$campaign->id}.pdf");
}
}
