<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'settings' => \App\Http\Middleware\ApplySettings::class,
            'maintenance' => \App\Http\Middleware\CheckMaintenance::class,
        ]);
        $middleware->append(\App\Http\Middleware\ApplySettings::class);
        
        // Add maintenance middleware to web group
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\CheckMaintenance::class,
        ]);
        
        // Or apply globally
        // $middleware->append(\App\Http\Middleware\CheckMaintenance::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();