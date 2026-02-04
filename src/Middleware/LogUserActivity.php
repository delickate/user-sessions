<?php

namespace Delickate\UserSessions\Middleware;

use Closure;
use Illuminate\Http\Request;
use Delickate\UserSessions\Models\UserSession;
use Delickate\UserSessions\Models\UserSessionActivity;

class LogUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!auth()->check()) {
            return $response;
        }

        $user = auth()->user();

        $session = UserSession::where('user_id', $user->id)
            ->whereNull('logout_at')
            ->latest()
            ->first();

        if (!$session) {
            return $response;
        }

        UserSessionActivity::create([
            'user_session_id' => $session->id,
            'user_id' => $user->id,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'route_name' => optional($request->route())->getName(),
            'payload' => $this->cleanPayload($request),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'hit_at' => now(),
        ]);

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
