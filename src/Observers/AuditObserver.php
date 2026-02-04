<?php

namespace Delickate\UserSessions\Observers;

use Delickate\UserSessions\Models\DbAuditLog;
use Illuminate\Support\Facades\Auth;
use Delickate\UserSessions\Models\UserSession;

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
        $userId = Auth::id();
        $session = session()->getId()
            ? UserSession::where('session_id', session()->getId())->first()
            : null;

        DbAuditLog::create([
            'user_id' => $userId,
            'user_session_id' => $session?->id,
            'table_name' => $model->getTable(),
            'operation' => $operation,
            'before' => $model->_audit_before ?? null,
            'after' => $operation === 'update' ? $model->getChanges() : null,
            'sql' => null, // optional, only for raw queries
            'bindings' => null,
            'executed_at' => now(),
        ]);

        // Clean up temporary property
        unset($model->_audit_before);
    }
}
