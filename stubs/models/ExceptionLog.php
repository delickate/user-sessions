<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExceptionLog extends Model
{
    protected $fillable = [
        'message',
        'file',
        'line',
        'trace',
        'url',
        'method',
        'ip',
        'user_id'
    ];
}
