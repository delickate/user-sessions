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
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\PasswordHistory;

class ChangePasswordController extends Controller
{
    public function show()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Validate input
        $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'confirmed',
                Password::min(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.'
            ]);
        }

        // Prevent reuse of last 4 passwords
        $lastPasswords = PasswordHistory::where('user_id', $user->id)
            ->latest()
            ->take(4)
            ->get();

        foreach ($lastPasswords as $oldPassword) {
            if (Hash::check($request->password, $oldPassword->password)) {
                return back()->withErrors([
                    'password' => 'You cannot reuse your last 4 passwords.'
                ]);
            }
        }

        // Store current password in history
        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
            'must_change_password' => false
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}