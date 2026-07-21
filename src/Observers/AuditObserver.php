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
namespace App\Observers;

use App\Models\DbAuditLog;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    /**
     * Handle model update event.
     */
    public function updated($model)
    {
        $this->log('updated', $model);
    }

    /**
     * Handle model delete event.
     */
    public function deleted($model)
    {
        $this->log('deleted', $model);
    }

    protected function log(string $operation, $model): void
    {
        // Prevent logging audit logs themselves
        if ($model->getTable() === 'user_audit_logs') {
            return;
        }

        // Only log configured models
        if (! in_array(get_class($model), config('activitylog.models', []))) {
            return;
        }

        $before = null;
        $after  = null;

        if ($operation === 'updated') {

            // Only log changed fields
            $dirty = $model->getDirty();

            if (empty($dirty)) {
                return;
            }

            $before = array_intersect_key(
                $model->getOriginal(),
                $dirty
            );

            $after = $dirty;
        }

        if ($operation === 'deleted') {
            $before = $model->getOriginal();
        }

        DbAuditLog::create([
            'table_name'  => $model->getTable(),
            'operation'   => $operation,
            'before'      => $before,
            'after'       => $after,
            'user_id'     => Auth::id(),
            'session_id'  => config('activitylog.log_session')
                ? session()->getId()
                : null,
            'executed_at' => now(),
        ]);
    }
}
