<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\SystemLog;

class LogSuccessfulLogin 
{
    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        SystemLog::create([
            'user_id' => $event->user->id,
            'action' => 'Inicio de sesión',
        ]);
    }
}
