<?php

namespace Delickate\UserSessions\Models;

use Illuminate\Database\Eloquent\Model;

class UserSessionActivity extends Model
{
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
