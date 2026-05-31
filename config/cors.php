<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Configure cross-origin resource sharing (CORS) for the application API.
    | In production, restrict allowed_origins to your own domain(s) via the
    | CORS_ALLOWED_ORIGINS environment variable.
    |
    | Example: CORS_ALLOWED_ORIGINS=https://app.example.com,https://admin.example.com
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    // In production set CORS_ALLOWED_ORIGINS to your domain(s)
    'allowed_origins' => array_filter(
        explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost,http://localhost:3000,http://localhost:8000'))
    ),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Content-Type', 'X-Requested-With', 'Authorization', 'Accept', 'X-CSRF-TOKEN'],

    'exposed_headers' => ['X-RateLimit-Limit', 'X-RateLimit-Remaining'],

    'max_age' => 600,

    'supports_credentials' => true,

];
