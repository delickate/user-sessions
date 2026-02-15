<?php

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

        $session = UserSessionImplement::where('user_id', $user->id)->whereNull('logout_at')->latest()->first();

        if (!$session) 
        {
            return $response;
        }

        UserSessionActivityImplement::create([
            'user_session_id'   => $session->id,
            'user_id'           => $user->id,
            'method'            => $request->method(),
            'url'               => $request->fullUrl(),
            'route_name'        => optional($request->route())->getName(),
            'payload'           => $this->cleanPayload($request),
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
