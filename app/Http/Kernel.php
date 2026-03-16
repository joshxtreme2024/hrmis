<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // global middleware
    ];

    protected $middlewareGroups = [
        'web' => [
            // web middleware
        ],
        'api' => [
            // api middleware
        ],
    ];

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        // add your admin middleware here:
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ];
}