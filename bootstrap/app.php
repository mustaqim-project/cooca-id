<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SecurityHeadersMiddleware;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/web.php',
            __DIR__ . '/../routes/admin.php',
            __DIR__ . '/../routes/customer.php',
            __DIR__ . '/../routes/affiliator.php',
        ],
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            SecurityHeadersMiddleware::class,
            \App\Http\Middleware\ActivityLogger::class,
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->api(append: [
            SecurityHeadersMiddleware::class,
        ]);

        // Alias untuk multi-guard
        $middleware->alias([
            'admin' => \App\Http\Middleware\Admin\AdminAuth::class,
            'customer' => \App\Http\Middleware\Customer\CustomerAuth::class,
            'affiliator' => \App\Http\Middleware\Affiliator\AffiliatorAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
