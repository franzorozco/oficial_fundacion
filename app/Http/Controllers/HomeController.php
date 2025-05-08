<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\DonationRequest;

class HomeController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalCampaigns = Campaign::count();
        $totalDonations = Donation::count();
        $pendingRequests = DonationRequest::where('state', 'pendiente')->count();

        return view('dashboard', compact(
            'totalUsers',
            'totalCampaigns',
            'totalDonations',
            'pendingRequests'
        ));
    }
}
