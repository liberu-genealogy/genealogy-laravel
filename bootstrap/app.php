<?php

use App\Http\Middleware\SecurityHeaders;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

if (! class_exists(Form::class) && class_exists(Schema::class)) {
    class_alias(Schema::class, Form::class);
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // Register the /broadcasting/auth route and load routes/channels.php so the
    // private-channel authorization (e.g. research-space.{id}) actually runs.
    // Enforcement still requires a real driver (BROADCAST_DRIVER=reverb — the env
    // key config/broadcasting.php reads — plus a running Reverb server).
    ->withBroadcasting(__DIR__.'/../routes/channels.php')
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(SecurityHeaders::class);
        $middleware->validateCsrfTokens(except: [
            'stripe/*',
        ]);
        $middleware->statefulApi();
        // api RateLimiter lives in RouteServiceProvider, which is never registered;
        // inline 60/min throttle avoids that dependency (mirrors its perMinute(60)).
        $middleware->throttleApi('60,1');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
