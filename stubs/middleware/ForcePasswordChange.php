<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user) {

            // 1️⃣ Force change flag (admin reset / first login)
            if ($user->must_change_password) {
                if (!$request->routeIs('password.change.*')) {
                    return redirect()->route('password.change.form')
                        ->with('error', 'You must change your password before continuing.');
                }
            }

            // 2️⃣ 90-day expiration rule
            if ($user->password_changed_at) {
                $expiryDate = Carbon::parse($user->password_changed_at)->addDays(90);

                if (now()->greaterThan($expiryDate)) {
                    if (!$request->routeIs('password.change.*')) {
                        return redirect()->route('password.change.form')
                            ->with('error', 'Your password has expired (90 days). Please change it.');
                    }
                }
            }
        }

        return $next($request);
    }
}