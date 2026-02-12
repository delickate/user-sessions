<?php

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
