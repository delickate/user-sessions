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

class UserSessionsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/user-sessions.php',
            'user-sessions'
        );
    }

    public function boot()
    {
        //SANI: Register auth event listeners
        Event::listen(Login::class, LogLogin::class);
        Event::listen(Logout::class, LogLogout::class);

        //SANI: Load migrations automatically
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        //SANI: push middleware for browsing logs
        $router->pushMiddlewareToGroup('web', LogUserActivity::class);

        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/user-sessions.php' =>
                config_path('user-sessions.php'),
        ], 'user-sessions-config');
    }
}
