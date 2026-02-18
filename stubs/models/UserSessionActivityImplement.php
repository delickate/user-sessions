<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSessionActivityImplement extends Model
{
    use HasFactory;

    protected $table = 'user_session_activities';

    protected $fillable = [
        'user_session_id',
        'user_id',
        'route_name',
        'hit_at',
        'ip_address',
        'user_agent',
        'metadata',
        'created_at',
        'payload',
        'url',
        'method'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Parent user session
     */
    public function session()
    {
        return $this->belongsTo(UserSessionImplement::class, 'user_session_id');
    }

    /**
     * User who performed the action
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Related audit logs (DB changes)
     */
    public function auditLogs()
    {
        return $this->hasMany(DbAuditLog::class, 'user_session_id', 'user_session_id');
    }
}
