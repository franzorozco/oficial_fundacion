<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class ReceiverController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->input('q');

        return User::where('name', 'like', "%{$search}%")
            ->select('id', 'name')
            ->limit(10)
            ->get();
    }
}