<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [

        'api' => [
            \App\Http\Middleware\JwtFromCookieMiddleware::class, 
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'role'=>\App\Http\Middleware\RoleMiddleware::class,
        
    ];
}
