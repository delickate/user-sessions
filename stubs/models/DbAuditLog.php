<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DbAuditLog extends Model
{
    protected $table = 'user_audit_logs';

    protected $fillable = [
        'table_name',
        'operation',
        'before',
        'after',
        'user_id',
        'session_id',
        'executed_at',
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
        'executed_at' => 'datetime',
    ];

    /**
     * Relationships (optional, for UI later)
     */

    public function user()
    {
        // Do NOT hard-depend on App\User model
        return $this->belongsTo(
            config('auth.providers.users.model'),
            'user_id'
        );
    }

    public function userSession()
    {
        return $this->belongsTo(
            UserSession::class,
            'session_id',
            'session_id'
        );
    }

    /**
     * Scopes (very useful for filtering)
     */

    public function scopeForTable($query, $table)
    {
        return $query->where('table_name', $table);
    }

    public function scopeOperation($query, $operation)
    {
        return $query->where('operation', $operation);
    }

    public function scopeBetween($query, $from, $to)
    {
        return $query->whereBetween('executed_at', [$from, $to]);
    }
}
