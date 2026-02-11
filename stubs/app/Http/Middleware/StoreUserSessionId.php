<?php 

namespace Delickate\UserSessions\Middleware;

use Closure;
use Delickate\UserSessions\Models\UserSession;

class StoreUserSessionId
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (auth()->check()) {
            $sessionId = session()->getId();

            if ($sessionId) {
                $userSession = UserSession::where('user_id', auth()->id())
                    ->whereNull('session_id')
                    ->latest()
                    ->first();

                if ($userSession) {
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
