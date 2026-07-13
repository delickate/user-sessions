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

namespace App\Http\Middleware;

use Closure;
use App\Models\UserSessionImplement;

class StoreUserSessionIdImplement
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (auth()->check()) 
        {
            $sessionId = session()->getId();

            if ($sessionId) 
            {
                $userSession = UserSessionImplement::where('user_id', auth()->id())->whereNull('session_id')->latest()->first();

                if ($userSession) 
                {
                    $userSession->update([
                        'session_id' => $sessionId,
                    ]);

                    session()->put('current_user_session_id', $userSession->id);
                }
            }
        }

        return $response;
    }
}
