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
            'session_id' => $sessionId,
            'session_date' => now()->toDateString(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->put('current_user_session_id', $userSession->id);
    }
}
