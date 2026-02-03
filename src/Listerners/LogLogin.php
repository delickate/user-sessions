<?php

namespace Delickate\UserSessions\Src\Listeners;


use Illuminate\Auth\Events\Login;
use Delickate\UserSessions\Src\Models\UserSession;

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
            'session_date' => now()->toDateString(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
