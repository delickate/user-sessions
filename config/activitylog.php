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

use App\Models\User;
return [
    // Models for which activity should be recorded when requests involve them.
    // Use fully-qualified class names. Example: App\Models\Post::class
    'models' => [
        // Add model class names here if you want additional model-specific logging
            User::class,
            
        ],

    // If true, middleware will attempt to associate logs with a `sessions` row
    // by reading the current session id from the request (e.g., header or cookie).
    'log_session' => true,

    // Maximum length to store for payload/details when serializing request input
    'max_payload_length' => 65535,
];
