<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\TrustedDevice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $user = Auth::user();

    $ip = $request->ip();

    $trustedDevice = TrustedDevice::where('user_id', $user->id)
        ->where('ip_address', $ip)
        ->first();

    if (!$trustedDevice) {

        $code = rand(100000, 999999);

        session([
            '2fa_code' => $code,
            '2fa_user' => $user->id,
            'remember_device' => $request->boolean('remember')
        ]);

        Mail::raw("Tu código de autenticación es: $code", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Código de verificación');
        });

        Auth::logout();

        return redirect()->route('2fa.verify');
    }

    $request->session()->regenerate();

    return redirect()->intended(route('dashboard', absolute: false));
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}