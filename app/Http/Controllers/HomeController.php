<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\DonationItem;
use App\Models\DonationRequest;
use App\Models\DonationStatus;
use App\Models\DonationType;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\TaskAssignment;

class HomeController extends Controller
{
    public function index()
    {
        return view('dashboard', [

            // Tarjetas principales
            'totalUsers' => User::count(),
            'totalCampaigns' => Campaign::count(),
            'totalDonations' => Donation::count(),
            'pendingRequests' => DonationRequest::where('state', 'pendiente')->count(),

            // Usuarios por mes
            'usersByMonth' => User::selectRaw('MONTH(created_at) as m, COUNT(*) as total')
                ->groupBy('m')->pluck('total', 'm'),

            // Donaciones por mes
            'donationsByMonth' => Donation::selectRaw('MONTH(created_at) as m, COUNT(*) as total')
                ->groupBy('m')->pluck('total', 'm'),

            // Donaciones por estado
            'donationsByStatus' => Donation::join('donation_statuses', 'donations.status_id', '=', 'donation_statuses.id')
                ->selectRaw('donation_statuses.name as status, COUNT(*) as total')
                ->groupBy('status')->pluck('total', 'status'),

            // Donaciones por tipo de ítem
            'donationItemsByType' => DonationItem::join('donation_types', 'donation_items.donation_type_id', '=', 'donation_types.id')
                ->selectRaw('donation_types.name as type, COUNT(*) as total')
                ->groupBy('type')->pluck('total', 'type'),

            // Solicitudes por estado
            'requestsByState' => DonationRequest::selectRaw('state, COUNT(*) as total')
                ->groupBy('state')->pluck('total', 'state'),

            // Participantes por evento
            'participantsByEvent' => EventParticipant::join('events', 'event_participants.event_id', '=', 'events.id')
                ->selectRaw('events.name as event, COUNT(*) as total')
                ->groupBy('event')->pluck('total', 'event'),

            // Tareas por estado
            'tasksByState' => TaskAssignment::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')->pluck('total', 'status'),
        ]);
    }
}
