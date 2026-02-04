<?php

namespace Delickate\UserSessions\Observers;

use Delickate\UserSessions\Models\DbAuditLog;
use Illuminate\Support\Facades\Auth;
use Delickate\UserSessions\Models\UserSession;


use Illuminate\Support\Facades\DB;

class AuditObserver
{
    /**
     * Handle the "updating" event.
     * Store original values temporarily
     */
    public function updating($model)
    {
        // Store before state in memory only
        $model->_audit_before = $model->getOriginal();
    }

    /**
     * Handle the "updated" event.
     */
    public function updated($model)
    {
        $this->logAudit($model, 'update');
    }

    /**
     * Handle the "deleting" event.
     */
    public function deleting($model)
    {
        // Store before state
        $model->_audit_before = $model->getOriginal();
    }

    /**
     * Handle the "deleted" event.
     */
    public function deleted($model)
    {
        $this->logAudit($model, 'delete');
    }

    protected function logAudit($model, $operation)
    {
        // Get current user
        //$userId = Auth::id();
        // $session = session()->getId()
        //     ? UserSession::where('session_id', session()->getId())->first()
        //     : null;

        $userId = auth()->id();
        $session = UserSession::where('session_id', session()->getId())->first();

        // DB::listen(function ($query) {
        //     $sql = $query->sql;
        //     $bindings = $query->bindings;
        //     $connection = $query->connectionName;
        // });

        DbAuditLog::create([
            'user_id' => $userId,
            'user_session_id' => $session?->id,
            'connection' => $model->getConnectionName() ?? config('database.default'),
            'table_name' => $model->getTable(),
            'operation' => $operation,
            'before' => $model->_audit_before ?? null,
            'after' => $operation === 'update' ? $model->getChanges() : null,
            'sql' => null, 
            'bindings' => null,
            'executed_at' => now(),
        ]);

        // Clean up temporary property
        unset($model->_audit_before);
    }
}
