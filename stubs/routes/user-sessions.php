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
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;

//SANI: change password
    Route::middleware(['auth'])->group(function () 
    {
    	//SANI: user sessions
	    Route::get('/sessions', [UserSessionController::class, 'index'])
	        ->name('sessions');

	    Route::get('/user-sessions/{session_id}/activities', 
	    [UserSessionController::class, 'activities']
		)->name('user-sessions.activities');

	    Route::get('/user-sessions/{session_id}/audit-logs',
	    [UserSessionController::class, 'auditLogs']
		)->name('user-sessions.audit-logs');
	
    	Route::get('/change-password', [ChangePasswordController::class, 'show'])
        ->name('password.change.form');

    	Route::post('/change-password', [ChangePasswordController::class, 'update'])
        ->name('password.change.update');

    	//Forcefully change password
    	Route::middleware(['force.password.change'])->group(function () 
    	{
    		Route::get('/home', [HomeController::class, 'index']);
		});
	});