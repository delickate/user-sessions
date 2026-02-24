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