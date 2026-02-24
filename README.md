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


#### Middleware

Add the user sessions middleware in your app/Http/Kernel.php under the web middleware group (after authentication middleware):

1) \App\Http\Middleware\SecurityHeaders::class inside middleware array
2) 'user.sessions' inside middlewareGroups array

```php
protected $middleware = [
        //...
        //...
        \App\Http\Middleware\SecurityHeaders::class, //SANI: user-sessions
        \App\Http\Middleware\CheckPasswordExpiry::class, //SANI: user-sessions
    ];

protected $middlewareGroups = [
        'web' => [
            //...
            //...
            //\Illuminate\Auth\Middleware\Authenticate::class, 
            'user.sessions',  //SANI: user-sessions
        ],
    ];

protected $routeMiddleware = [
        //...
        //...
        'force.password.change' => \App\Http\Middleware\ForcePasswordChange::class,   //SANI: user-sessions
    ];
```

This middleware automatically logs user activity and stores session information.

#### Routes
```php
 //SANI: change password
    Route::middleware(['auth'])->group(function () 
    {
        //SANI: user sessions
        Route::get('/sessions', [UserSessionController::class, 'index'])
            ->name('sessions');

        Route::get('/user-sessions/{session_id}/activities', 
        [UserSessionController::class, 'activities']
        )->name('user-sessions.activities');

        Route::get('/user-sessions/{session_id}/audit-logs',
        [UserSessionController::class, 'auditLogs']
        )->name('user-sessions.audit-logs');
    
        //SANI: Change password & their rules
        Route::get('/change-password', [ChangePasswordController::class, 'show'])
        ->name('password.change.form');

        Route::post('/change-password', [ChangePasswordController::class, 'update'])
        ->name('password.change.update');

        //SANI: Forcefully change password
        Route::middleware(['force.password.change'])->group(function () 
        {
            Route::get('/home', [HomeController::class, 'index']);
        });
    });
```

#### Models

Open User model and add 'password_changed_at' in fillable like this

```php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'password_changed_at'   //SANI: user-sessions
    ];

```

#### Layout links
```html
<ul>
    <li><a href="{{ url('sessions') }}">Sessions</a></li>
    <li><a href="{{ url('/change-password') }}">Change password</a></li>
</ul>  
```
#### Publish Package Files

Publish all package configuration, views, routes, controllers, and middleware:

```bash
php artisan vendor:publish --tag=user-sessions  --force
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
 - Clickjacking Protection (Prevents the application from being embedded in iframe elements on external domains)
 - MIME-Type Sniffing Prevention (Prevents browsers from interpreting files as a different MIME type than declared)
 - Cross-Site Scripting (XSS) Protection (Legacy Browsers)
 - Referrer Policy Enforcement (Controls how much referrer information is shared during navigation)
 - Content Security Policy (Restricts the sources from which the browser may load resources)
 - HTTP Strict Transport Security (Forces browsers to use HTTPS for all future requests)


 ##### 8. Password Policy & Authentication Controls
- Strong password complexity enforcement
- Password history restriction (reuse prevention)
- Forced password change mechanisms
- 90-day password expiration enforcement
- Secure password storage using strong hashing
- Administrative forced reset capability
 

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
> php artisan session:table (only if session is being store into database.  its optional)
> composer require delickate/user-sessions
> php artisan vendor:publish --tag=user-sessions  --force
> php artisan migrate
```

To remove package run following commands

```bash
> composer remove delickate/user-sessions
> composer clear-cache
```



