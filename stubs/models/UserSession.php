<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $table = 'user_sessions';

    protected $fillable = [
        'user_id',
        'login_at',
        'logout_at',
        'ip_address',
        'user_agent',
        'session_date',
    ];

    protected $dates = [
        'login_at',
        'logout_at',
    ];

    protected $casts = [
    'login_at' => 'datetime',
    'logout_at' => 'datetime',
];
}
