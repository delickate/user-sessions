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
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSessionALogs extends Model
{
    protected $table = 'user_session_logs';

    protected $fillable = [
        'user_session_id',
        'user_id',
        'method',
        'url',
        'route_name',
        'payload',
        'ip_address',
        'user_agent',
        'hit_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'hit_at' => 'datetime',
    ];
}
