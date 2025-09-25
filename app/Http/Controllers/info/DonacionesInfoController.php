<?php

namespace App\Http\Controllers\info;


use App\Models\Campaign;
use App\Http\Controllers\Controller;

class DonacionesInfoController extends Controller
{
    public function index()
    {
        // Solo campañas visibles (show_cam = true)
        $campaigns = Campaign::where('show_cam', true)->get();

        return view('info-activity.donaciones', compact('campaigns'));
    }
}
