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
use Illuminate\Http\Request;

class AuditObserver
{
    /**
     * Handle model update event.
     */
    public function updating($model)
    {
        if (! $this->shouldLog($model)) {
            return;
        }

        $dirty = $model->getDirty();

        if (empty($dirty)) {
            return;
        }

        $before = array_intersect_key(
            $model->getOriginal(),
            $dirty
        );

        $after = $dirty;

        $this->storeLog($model, 'updated', $before, $after);
    }


    /**
     * Handle model delete event.
     */
    public function deleted($model)
    {
        if (! $this->shouldLog($model)) {
            return;
        }

        $before = $model->getOriginal();

        $this->storeLog($model, 'deleted', $before, null);
    }


    protected function shouldLog($model): bool
    {
        if ($model->getTable() === 'user_audit_logs') {
            return false;
        }

        return in_array(get_class($model), config('activitylog.models', []));
    }

    protected function storeLog($model, string $operation, $before, $after): void
    {
        $request = request(); 

        $user = auth()->user();

        $session = \App\Models\UserSessionImplement::where('user_id', $user?->id)
            ->latest()
            ->first();

        DbAuditLog::create([
            'table_name'      => $model->getTable(),
            'operation'       => $operation,
            'before'          => $before,
            'after'           => $after,
            'user_id'         => auth()->id(),
            'user_session_id' => $session?->session_id,
            'executed_at'     => now(),

            // Request data
            'method'      => $request->method(),
            'url'         => $request->fullUrl(),
            'route_name'  => optional($request->route())->getName(),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),

            // Exclude sensitive fields
            'payload'     => json_encode(
                $this->cleanPayload($request->except(['password', 'password_confirmation']))
            ),
        ]);
    }

    protected function cleanPayload(array $payload): array
    {
        unset($payload['_token']);

        return $payload;
    }

}
