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

    }
}
