<?php

Route::middleware([
    'web',
    'auth',
    config('user-sessions.ui.middleware', 'can:viewAuditLogs'),
])
->prefix(config('user-sessions.ui.route_prefix', 'admin/user-sessions'))
->name('user-sessions.')
->group(function () 
{

    Route::get('/', [SessionController::class, 'index'])
        ->name('sessions');

    Route::get('/{session}/activities', [ActivityController::class, 'index'])
        ->name('activities');

    Route::get('/activities/{activity}', [ActivityController::class, 'show'])
        ->name('activity.detail');
});
