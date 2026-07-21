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

return [

    /*
    |--------------------------------------------------------------------------
    | Enable / Disable User Session Tracking
    |--------------------------------------------------------------------------
    */
    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    | Which guards should be tracked
    */
    'guards' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Session Table Name
    |--------------------------------------------------------------------------
    */
    'table' => 'user_sessions',

    /*
    |--------------------------------------------------------------------------
    | Track IP Address & User Agent
    |--------------------------------------------------------------------------
    */
    'track_ip' => true,
    'track_user_agent' => true,

    /*
    |--------------------------------------------------------------------------
    | Active Session Logic
    |--------------------------------------------------------------------------
    | If true, logout_at NULL means active
    */
    'use_logout_timestamp' => true,

    /*
    |--------------------------------------------------------------------------
    | Default Listing Filters
    |--------------------------------------------------------------------------
    */
    'default_filters' => [
        'date' => 'today', // today | yesterday | this_week
        'per_page' => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cleanup Settings
    |--------------------------------------------------------------------------
    | Automatically delete old session logs
    */
    'cleanup' => [
        'enabled' => true,
        'keep_days' => 90,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Settings (future UI)
    |--------------------------------------------------------------------------
    */
    'ui' => [
        'route_prefix' => 'admin/sessions',
        'middleware' => ['web', 'auth'],
    ],

    'audit_models' => [
                        App\Models\User::class,
                        
                      ],

    'modules' => [],

];
