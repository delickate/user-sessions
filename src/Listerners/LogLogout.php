<?php

namespace Delickate\UserSessions\Listeners;

use Illuminate\Auth\Events\Logout;
use Delickate\UserSessions\Models\UserSession;

class LogLogout
{
    public function handle(Logout $event)
    {
        UserSession::where('user_id', $event->user->id)
            ->whereNull('logout_at')
            ->limit(1)
            ->update([
                'logout_at' => now(),
            ]);
    }
}
