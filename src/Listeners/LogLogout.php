<?php

namespace Delickate\UserSessions\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\UserSessionImplement;

class LogLogout
{
    public function handle(Logout $event)
    {
        UserSessionImplement::where('user_id',  optional($event->user)->id)
            ->whereNull('logout_at')
            ->limit(1)
            ->update([
                'logout_at' => now(),
            ]);
    }
}
