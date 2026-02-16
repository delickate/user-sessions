<?php 

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
