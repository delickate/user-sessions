<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPasswordExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->password_changed_at) {
            if ($user->password_changed_at->addDays(90)->isPast()) {
                if (!$request->is('change-password')) {
                    return redirect()->route('password.change.form')
                        ->with('error', 'Your password has expired. Please change it.');
                }
            }
        }

        return $next($request);
    }
}
