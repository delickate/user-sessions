# User Sessions

**User Sessions** is a Laravel package for tracking user activity, managing user sessions, and logging detailed session and request data. It is designed to enhance auditing, analytics, and security in your Laravel applications.

---

## 📦 Installation

Install the package via Composer:

```bash
composer require delickate/user-sessions
```

Or install the latest development version:

```bash
composer require delickate/user-sessions:dev-main --prefer-source
```
#### Remove / Uninstall

To uninstall the package:

```bash
composer remove delickate/user-sessions
```
Clear Laravel caches after installation/removal:

```bash
php artisan cache:clear
php artisan config:clear
composer dump-autoload
php artisan optimize:clear
```
After installing, run the migrations to create required database tables:

```bash
php artisan migrate
```

SESSION_DRIVER=database

#### Middleware

Add the user sessions middleware in your app/Http/Kernel.php under the web middleware group (after authentication middleware):

```php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,

        // User Sessions Middleware
        'user.sessions',
    ],
];
```

This middleware automatically logs user activity and stores session information.

#### Publish Package Files

Publish all package configuration, views, routes, controllers, and middleware:

```bash
php artisan vendor:publish --tag=user-sessions
```

## ⚡ Features

User Sessions package provides the following features:

##### 1. User Session Tracking

 - Automatically tracks user sessions.
 - Stores session ID, login/logout times, and session metadata.
 - Supports multiple simultaneous sessions per user.

##### 2. Activity Logging

##### Logs each user request, including:
 - HTTP method (GET, POST, etc.)
 -  URL and route name
 - Payload (excluding sensitive data like passwords)
 - IP address and user agent
 - Timestamp of the request
 -  Payload is stored safely as JSON in the database.

##### 3. Audit Logging

 - Tracks database changes for configured models.
 - Logs UPDATE and DELETE queries automatically.
 - Prevents logging of system tables to avoid infinite loops.
 - Supports model observers for detailed audit trails.

##### 4. Login & Logout Event Listeners

 - Logs every user login and logout event.
 - Automatically associates events with user session records.

##### 5. Middleware-Based Architecture

 - user.sessions middleware handles request logging.
 - store.user.session middleware stores session IDs for the currently logged-in user.
 - Easy to customize or extend.

##### 6. Customizable Views & Routes

 - Optional UI for viewing user sessions and activity.
 - Configurable routes and views.
 - Fully publishable for customization.

##### 7. Secure & Lightweight

 - Excludes sensitive fields (passwords, tokens) from logs.
 - Minimal overhead and fully compatible with Laravel's session system



---

This version includes:

- Installation & uninstall instructions  
- Middleware setup  
- Publishing files  
- Detailed feature list  
- Configuration & usage examples  
- Troubleshooting  
- License & contributing info  

Here is list of commands in order to run

```bash
> php artisan session:table
> composer require delickate/user-sessions
> php artisan vendor:publish --tag=user-sessions
> php artisan migrate
```

To remove package run following commands

```bash
> composer remove delickate/user-sessions
> composer clear-cache
```



