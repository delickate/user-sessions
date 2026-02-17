<?php 

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