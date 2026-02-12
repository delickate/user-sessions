<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelChangeLog extends Model
{
    protected $table = 'user_audit_logs';
    
    protected $fillable = [
        'user_session_id',
        'user_id',
        'model_type',
        'model_id',
        'before',
        'after',
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];
}
