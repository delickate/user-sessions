<?php

namespace App\Observers;

use App\Models\UserActivityLog;
use App\Models\Session as UserSession;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class AuditObserver
{
    // Use 'updating' to capture originals before Eloquent syncs them
    public function updating($model)
    {
        try {
            $dirty = $model->getDirty();
            if (empty($dirty)) {
                return;
            }

            $original = $model->getOriginal();
            $old = [];
            $new = [];

            foreach ($dirty as $key => $value) {
                if (in_array($key, ['password', 'remember_token'])) {
                    continue;
                }
                $old[$key] = array_key_exists($key, $original) ? $original[$key] : null;
                $new[$key] = $model->{$key} ?? null;
            }

            $oldJson = Str::limit(json_encode($old, JSON_UNESCAPED_UNICODE), Config::get('activitylog.max_payload_length', 65535));
            $newJson = Str::limit(json_encode($new, JSON_UNESCAPED_UNICODE), Config::get('activitylog.max_payload_length', 65535));

            $sessionId = null;
            if (Config::get('activitylog.log_session')) {
                try {
                    if (auth()->check()) {
                        $query = UserSession::where('user_id', auth()->id());
                        $req = request();
                        if ($req && $req->ip()) {
                            $query->where('ip_address', $req->ip());
                        }
                        if ($req && $req->userAgent()) {
                            $query->where('user_agent', 'like', Str::limit($req->userAgent(), 191));
                        }
                        $recent = $query->orderByRaw('COALESCE(created_at, FROM_UNIXTIME(last_activity)) DESC')->first();
                        if (!empty($recent)) {
                            $sessionId = $recent->id;
                        }
                    }
                } catch (\Throwable $e) {
                    // ignore
                }
            }

            UserActivityLog::create([
                'user_id' => auth()->id() ?? null,
                'session_id' => $sessionId,
                'action' => 'updated ' . class_basename($model) . ' id:' . $model->getKey(),
                'details' => null,
                'payload' => null,
                'ip_address' => request()->ip() ?? null,
                'user_agent' => Str::limit(request()->userAgent() ?? null, 1000),
                'model_type' => get_class($model),
                'model_id' => $model->getKey(),
                'old_values' => $oldJson,
                'new_values' => $newJson,
            ]);
        } catch (\Throwable $e) {
            // Don't block the request on logging failure
        }
    }

    // Use 'deleting' to capture attributes before the model is removed
    public function deleting($model)
    {
        try {
            $original = $model->getOriginal();
            $old = [];
            foreach ($original as $key => $value) {
                if (in_array($key, ['password', 'remember_token'])) {
                    continue;
                }
                $old[$key] = $value;
            }

            $oldJson = Str::limit(json_encode($old, JSON_UNESCAPED_UNICODE), Config::get('activitylog.max_payload_length', 65535));

            $sessionId = null;
            if (Config::get('activitylog.log_session')) {
                try {
                    if (auth()->check()) {
                        $query = UserSession::where('user_id', auth()->id());
                        $req = request();
                        if ($req && $req->ip()) {
                            $query->where('ip_address', $req->ip());
                        }
                        if ($req && $req->userAgent()) {
                            $query->where('user_agent', 'like', Str::limit($req->userAgent(), 191));
                        }
                        $recent = $query->orderByRaw('COALESCE(created_at, FROM_UNIXTIME(last_activity)) DESC')->first();
                        if (!empty($recent)) {
                            $sessionId = $recent->id;
                        }
                    }
                } catch (\Throwable $e) {
                    // ignore
                }
            }

            UserActivityLog::create([
                'user_id' => auth()->id() ?? null,
                'session_id' => $sessionId,
                'action' => 'deleted ' . class_basename($model) . ' id:' . $model->getKey(),
                'details' => null,
                'payload' => null,
                'ip_address' => request()->ip() ?? null,
                'user_agent' => Str::limit(request()->userAgent() ?? null, 1000),
                'model_type' => get_class($model),
                'model_id' => $model->getKey(),
                'old_values' => $oldJson,
                'new_values' => null,
            ]);
        } catch (\Throwable $e) {
            // swallow
        }
    }
}
