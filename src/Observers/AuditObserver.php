<?php

namespace Delickate\UserSessions\Observers;

use App\Models\DbAuditLog;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSessionImplement;


use Illuminate\Support\Facades\DB;
use App\Models\UserSessionActivityImplement;

class AuditObserver
{
    public function created($model)
    {
        $this->log($model, 'create', null, $model->getAttributes());
    }

    public function updating($model)
    {
        $this->log(
            $model,
            'update',
            $model->getOriginal(),
            $model->getDirty()
        );
    }

    public function deleted($model)
    {
        $this->log($model, 'delete', $model->getOriginal(), null);
    }

    protected function log($model, $operation, $before, $after)
    {
        DbAuditLog::create([
            'user_id' => auth()->id(),
            'user_session_id' => null, // we’ll fix later
            'table_name' => $model->getTable(),
            'operation' => $operation,
            'before' => $before,
            'after' => $after,
            'executed_at' => now(),
        ]);
    }
}

