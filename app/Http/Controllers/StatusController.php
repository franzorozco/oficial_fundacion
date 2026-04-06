<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonationStatus;

class StatusController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->input('q');

        return DonationStatus::where('name', 'like', "%{$search}%")
            ->select('id', 'name')
            ->limit(10)
            ->get();
    }
}