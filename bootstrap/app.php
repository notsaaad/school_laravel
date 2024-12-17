<?php

use App\Http\Middleware\checkrole;

use App\Http\Middleware\isAdmin;

use App\Http\Middleware\isUser;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::middleware(['web'])
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'auth', "isAdmin"])
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));


            Route::middleware(['web', 'auth' , "isUser"])
                ->prefix('students')
                ->group(base_path('routes/users.php'));
        },
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(["isAdmin" => isAdmin::class, "isUser" =>  isUser::class, "checkRole" => checkrole::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
