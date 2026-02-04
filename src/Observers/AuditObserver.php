<?php 

namespace Delickate\UserSessions\Observers;

use Delickate\UserSessions\Models\ModelChangeLog;
use Delickate\UserSessions\Models\UserSession;

class AuditObserver
{
    public function updating($model)
    {
        $model->_audit_before = $this->getDirtyOriginals($model);
    }

    public function updated($model)
    {
        if (!auth()->check()) return;

        $session = UserSession::where('session_id', session()->getId())->first();
        if (!$session) return;

        ModelChangeLog::create([
            'user_session_id' => $session->id,
            'user_id' => auth()->id(),
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            //'before' => $model->_audit_before ?? null,
            'after' => $model->getChanges(),
        ]);

        unset($model->_audit_before);
    }

    protected function getDirtyOriginals($model)
    {
        $before = [];

        foreach ($model->getDirty() as $key => $value) {
            $before[$key] = $model->getOriginal($key);
        }

        return $before;
    }
}
