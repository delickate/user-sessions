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

class UserSessionImplement extends Model
{
    protected $table = 'user_sessions';

    protected $fillable = [
        'user_id',
        'login_at',
        'logout_at',
        'ip_address',
        'user_agent',
        'session_date',
        'session_id'
    ];

    protected $dates = [
        'login_at',
        'logout_at',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activities()
    {
        return $this->hasMany(UserSessionActivities::class, 'session_id', 'id');
    }

    public function auditLogs()
    {
        return $this->hasMany(DbAuditLog::class, 'user_session_id', 'session_id');
    }

}
