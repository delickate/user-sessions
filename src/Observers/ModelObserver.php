<?php 

namespace Vendor\UserSessions\Observers;

class ModelObserver
{
    public function created($model)
    {
        if (!config('user-sessions.events.created')) {
            return;
        }

        // log session
    }

    public function updated($model)
    {
        if (!config('user-sessions.events.updated')) {
            return;
        }

        // log session
    }

    public function deleted($model)
    {
        if (!config('user-sessions.events.deleted')) {
            return;
        }

        // log session
    }
}
