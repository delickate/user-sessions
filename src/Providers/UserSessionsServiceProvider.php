<?php

namespace Delickate\UserSessions\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Delickate\UserSessions\Listeners\LogLogin;
use Delickate\UserSessions\Listeners\LogLogout;

use Illuminate\Routing\Router;
use Delickate\UserSessions\Middleware\LogUserActivity;
use Delickate\UserSessions\Observers\AuditObserver;

use Illuminate\Support\Facades\DB;
use Delickate\UserSessions\Models\UserSession;
use Delickate\UserSessions\Models\DbAuditLog;

class UserSessionsServiceProvider extends ServiceProvider
{
    public function register()
    {
        //SANI: load config
        $this->mergeConfigFrom(__DIR__ . '/../../config/user-sessions.php', 'user-sessions'
        );
    }

    public function boot()
    {
        //SANI: Register auth event listeners
        Event::listen(Login::class, LogLogin::class);
        Event::listen(Logout::class, LogLogout::class);

        //SANI: Load migrations automatically
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        
        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/user-sessions.php' =>
                config_path('user-sessions.php'),
        ], 'user-sessions-config');


        //SANI: push middleware for browsing logs
        $router = $this->app['router'];
        //$router->pushMiddlewareToGroup('web', LogUserActivity::class);
        //$router->appendMiddlewareToGroup('web', LogUserActivity::class);
        $router->aliasMiddleware('user.sessions', LogUserActivity::class);

        //SANI: loging audit trail for specific models
        foreach (config('user-sessions.audit_models', []) as $model) 
        {
            $model::observe(AuditObserver::class);
        }

        //SANI: Query logs
        DB::listen(function ($query) 
        {
            $this->logQuery($query);
        });

    }

    protected function logQuery($query)
    {
        $sql = strtolower(trim($query->sql));

        //if (!preg_match('/^(insert|update|delete)/', $sql, $matches)) 
        if (!preg_match('/^(update|delete)/', $sql, $matches)) 
        {
            return; // ignore selects
        }

        if (str_contains($sql, 'db_audit_logs') ||
            str_contains($sql, 'migrations') ||
            str_contains($sql, 'cache') ||
            str_contains($sql, 'cache_locks') ||
            str_contains($sql, 'jobs') ||
            str_contains($sql, 'failed_jobs') ||
            str_contains($sql, 'user_session_activities') || 
            str_contains($sql, 'user_session_model_changes') || 
            str_contains($sql, 'user_sessions')
           ) 
        {
            return; // prevent infinite loop
        }

        $operation = $matches[1];
        $table = $this->extractTableName($sql, $operation);

        $userId = auth()->id();
        $session = session()->getId()
            ? UserSession::where('session_id', session()->getId())->first()
            : null;

        DbAuditLog::create([
            'user_id' => $userId,
            'user_session_id' => $session?->id,
            'connection' => $query->connectionName,
            'operation' => $operation,
            'table_name' => $table,
            'sql' => $query->sql,
            'bindings' => $query->bindings,
            'executed_at' => now(),
        ]);
    }


    protected function extractTableName($sql, $operation)
    {
        return match ($operation) {
            'insert' => preg_replace('/insert into\s+([^\s]+)/', '$1', $sql),
            'update' => preg_replace('/update\s+([^\s]+)/', '$1', $sql),
            'delete' => preg_replace('/delete from\s+([^\s]+)/', '$1', $sql),
            default => null,
        };
    }


}
