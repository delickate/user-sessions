<?php

namespace Delickate\UserSessions\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Delickate\UserSessions\Listeners\LogLogin;
use Delickate\UserSessions\Listeners\LogLogout;

use Illuminate\Routing\Router;
use App\Http\Middleware\LogUserActivityImplement;

use Illuminate\Support\Facades\DB;
use App\Models\UserSessionImplement;
use App\Models\DbAuditLog;
use Delickate\UserSessions\Observers\AuditObserver;
use App\Http\Middleware\StoreUserSessionIdImplement;



class UserSessionsServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Load config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/user-sessions.php',
            'user-sessions'
        );
    }

    public function boot()
    {
        // Register auth event listeners
        Event::listen(Login::class, LogLogin::class);
        Event::listen(Logout::class, LogLogout::class);

        // Load migrations automatically
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        if (config('user-sessions.ui.enabled')) 
        {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
            $this->loadViewsFrom(__DIR__ . '/../../resources/views','user-sessions');
        }

        //publish all
        $this->publishes([
        // Config
        __DIR__.'/../../config/user-sessions.php' =>
            config_path('user-sessions.php'),

        __DIR__.'/../../config/activitylog.php' =>
            config_path('activitylog.php'),

        // Controllers
        __DIR__.'/../../stubs/controllers' =>
            app_path('Http/Controllers/UserSessions'),

        // Controllers
        __DIR__.'/../../stubs/models' =>
            app_path('Models'),

        // middleware
        __DIR__.'/../../stubs/middleware' =>
            app_path('Http/Middleware'),


        // Views
        __DIR__.'/../../stubs/views' =>
            resource_path('views/user-sessions'),

        // Routes
        __DIR__.'/../../stubs/routes/user-sessions.php' =>
            base_path('routes/user-sessions.php'),

        //observer
        __DIR__.'/../../stubs/Observers/AuditObserver.php' =>
            app_path('Observers/AuditObserver.php'),


    ], 'user-sessions');
        
        // Publish config
        // $this->publishes([
        //     __DIR__ . '/../../config/user-sessions.php' =>
        //         config_path('user-sessions.php'),
        // ], 'user-sessions-config');



        // // Controllers
        // $this->publishes([
        //     __DIR__.'/../../stubs/controllers' =>
        //         app_path('Http/Controllers/UserSessions'),
        // ], 'user-sessions-controllers');

        // // Views
        // $this->publishes([
        //     __DIR__.'/../../stubs/views' =>
        //         resource_path('views/user-sessions'),
        // ], 'user-sessions-views');


        // // Routes
        // $this->publishes([
        //     __DIR__.'/../../stubs/routes/user-sessions.php.stub' =>
        //         base_path('routes/user-sessions.php'),
        // ], 'user-sessions-routes');



        // Middleware alias
        $router = $this->app['router'];
        //$router->aliasMiddleware('user.sessions', LogUserActivityImplement::class);
        $router->aliasMiddleware('user.sessions', LogUserActivityImplement::class);

        $router->aliasMiddleware('store.user.session', StoreUserSessionIdImplement::class);


        // Query listener for audit logs

        foreach (config('user-sessions.audit_models', []) as $modelClass) 
        {
            if (class_exists($modelClass)) {
                $modelClass::observe(AuditObserver::class);
            }
        }


        // foreach (config('user-sessions.models', []) as $model) 
        // {
        //     $model::observe(ModelObserver::class);
        // }

        DB::listen(function ($query) {
            $this->logQuery($query);
        });
    }

    protected function logQuery($query)
    {
        $sql = strtolower(trim($query->sql));

        // Only log UPDATE & DELETE queries
        if (!preg_match('/^(update|delete)/', $sql, $matches)) {
            return;
        }

        // Prevent infinite loop (do not log audit tables or system tables)
        if (
            str_contains($sql, 'db_audit_logs') ||
            str_contains($sql, 'migrations') ||
            str_contains($sql, 'cache') ||
            str_contains($sql, 'cache_locks') ||
            str_contains($sql, 'jobs') ||
            str_contains($sql, 'failed_jobs') ||
            str_contains($sql, 'user_session_activities') ||
            str_contains($sql, 'user_session_model_changes') ||
            str_contains($sql, 'user_sessions')
        ) {
            return;
        }

        $operation = $matches[1];
        $table = $this->extractTableName($sql, $operation);

        $userId = auth()->id();
        $session = session()->getId()
            ? UserSessionImplement::where('session_id', session()->getId())->first()
            : null;

        // Save only what we can get from query
        DbAuditLog::create([
            'user_id' => $userId,
            'user_session_id' => $session?->id,
            'connection' => $query->connectionName,
            'operation' => $operation,
            'table_name' => $table,
            'before' => null,   
            'after' => null,   
            'sql' => $query->sql,
            'bindings' => json_encode($query->bindings),
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

class ModuleRegistry
{
    protected static array $modules = [];

    public static function register(string $name, array $data = [])
    {
        self::$modules[$name] = $data;
    }

    public static function all()
    {
        return self::$modules;
    }
}

