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
use Illuminate\Http\Request;
use App\Models\UserSessionImplement;
use App\Models\UserSessionActivityImplement;

class LogUserActivityImplement
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!auth()->check()) 
        {
            return $response;
        }

        $user = auth()->user();

        $session = UserSessionImplement::where('user_id', $user->id)->latest()->first();

        if (!$session) 
        {
            return $response;
        }

        UserSessionActivityImplement::create([
            'user_session_id'   => $session->session_id,
            'user_id'           => $user->id,
            'method'            => $request->method(),
            'url'               => $request->fullUrl(),
            'route_name'        => optional($request->route())->getName(),
            'payload'           => json_encode($this->cleanPayload($request)),
            'ip_address'        => $request->ip(),
            'user_agent'        => $request->userAgent(),
            'hit_at'            => now(),
        ]);

        session()->put('current_user_session_id', $session->id);


        return $response;
    }

    protected function cleanPayload(Request $request)
    {
        $data = $request->except([
            'password',
            'password_confirmation',
            '_token',
        ]);

        return empty($data) ? null : $data;
    }
}
