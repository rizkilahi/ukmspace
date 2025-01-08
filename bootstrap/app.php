<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUKM;
use App\Http\Middleware\IsUser;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            // Middleware bawaan Laravel
            \Illuminate\Http\Middleware\TrustProxies::class,
            \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        // Middleware custom dengan alias
        $middleware->alias([
            'isAdmin' => IsAdmin::class,
            'isUKM' => IsUKM::class,
            'isUser' => IsUser::class,
        ]);
        // $middleware->redirectGuestTo('/login');

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
