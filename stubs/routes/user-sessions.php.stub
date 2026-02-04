<?php 

use App\Http\Controllers\UserSessions\UserSessionController;

Route::middleware(['web', 'auth'])
    ->prefix('admin/user-sessions')
    ->group(function () {
        Route::get('/', [UserSessionController::class, 'index'])
            ->name('user-sessions.index');
    });
