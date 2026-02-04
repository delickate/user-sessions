# user-sessions
Install it using following command
> composer  require delickate/user-sessions
or
> composer require delickate/user-sessions:dev-main --prefer-source

To uninstall this package. use following command
> composer remove delickate/user-sessions

Clear the cache as well
> composer clear-cache
> php artisan cache:clear
> php artisan config:clear
> composer dump-autoload
> php artisan optimize:clear


After installation, run migration command
> php artisan migrate

After installing the package, add the middleware **after auth**
in `app/Http/Kernel.php`:

'protected $middlewareGroups = [
        'web' => [
            ...
            ...
            //\Illuminate\Auth\Middleware\Authenticate::class,
            'user.sessions',
        ],

],

After installation publish the files as well

> php artisan vendor:publish --tag=user-sessions-config

