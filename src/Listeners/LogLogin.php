<?php

namespace Delickate\UserSessions\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\UserSessionImplement;

class LogLogin
{
    public function handle(Login $event)
    {
        if (!config('user-sessions.enabled')) {
            return;
        }

        $sessionId = session()->getId();


        $userSession = UserSessionImplement::create([
            'user_id' => $event->user->id,
            'login_at' => now(),
            //'session_id' => $sessionId,
            'session_date' => now()->toDateString(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->put('current_user_session_id', $userSession->id);
    }
}
