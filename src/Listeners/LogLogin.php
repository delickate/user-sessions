<?php

namespace Delickate\UserSessions\Listeners;

use Illuminate\Auth\Events\Login;
use Delickate\UserSessions\Models\UserSession;

class LogLogin
{
    public function handle(Login $event)
    {
        if (!config('user-sessions.enabled')) {
            return;
        }


        UserSession::create([
            'user_id' => $event->user->id,
            'login_at' => now(),
            'session_id' => session()->getId(),
            'session_date' => now()->toDateString(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
