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
