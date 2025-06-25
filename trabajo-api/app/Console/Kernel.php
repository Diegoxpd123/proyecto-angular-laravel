<?php

namespace App\Http;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'web' => [
            // middleware web...
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
        // ...
        'tenant' => \App\Http\Middleware\IdentifyTenant::class,
    ];

    protected $commands = [
        \App\Console\Commands\TenantCreate::class,
        \App\Console\Commands\TenantsMigrate::class,
    ];
}
