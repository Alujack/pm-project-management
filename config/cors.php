<?php


return [

    'paths' => ['api/*', 'auth/*', 'sanctum/csrf-cookie','broadcasting/*', 'broadcasting/auth'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',
        'https://pm-project-management-frontend.vercel.app',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    // 'exposed_headers' => [],

    'exposed_headers' => ['Content-Disposition'],

    'max_age' => 0,

    'supports_credentials' => true,

];
