<?php

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
