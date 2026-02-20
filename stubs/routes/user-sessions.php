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
use App\Http\Controllers\UserSessions\UserSessionController;

Route::middleware(['web', 'auth'])
    ->prefix('admin/user-sessions')
    ->group(function () {
        Route::get('/', [UserSessionController::class, 'index'])
            ->name('admin.user-sessions');
    });


    Route::get('/sessions/{id}/activities', [SessionsController::class, 'activities'])
    ->middleware('auth')
    ->name('sessions.activities');