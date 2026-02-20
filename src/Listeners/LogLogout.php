<?php
/**
 * --------------------------------------------------------------------------
 * Delickate User Sessions Package
 * --------------------------------------------------------------------------
 *
 * @package     Delickate\UserSessions
 * @author      Sani Hyne 
 * @copyright   Copyright (c) 2026 Delickate
 * @license     MIT
 * @version     1.0.0
 * @since       1.0.0
 *
 * This file is part of the Delickate User Sessions module.
 * It provides session tracking, activity logging, and audit features.
 *
 */
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
