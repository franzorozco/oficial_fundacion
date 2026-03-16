<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TrustedDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function index()
    {
        return view('auth.verify-2fa');
    }

    public function verify(Request $request)
    {

        if ($request->code != session('2fa_code')) {
            return back()->withErrors([
                'code' => 'Código incorrecto'
            ]);
        }

        $user = User::find(session('2fa_user'));

        Auth::login($user);

        if (session('remember_device')) {

TrustedDevice::firstOrCreate([
    'user_id' => $user->id,
    'ip_address' => $request->ip()
]);

        }

        session()->forget([
            '2fa_code',
            '2fa_user',
            'remember_device'
        ]);

        return redirect()->route('dashboard');
    }
}