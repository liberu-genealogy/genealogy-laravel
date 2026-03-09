<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

if (! class_exists(\Filament\Forms\Form::class) && class_exists(\Filament\Schemas\Schema::class)) {
    class_alias(\Filament\Schemas\Schema::class, \Filament\Forms\Form::class);
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->validateCsrfTokens(except: [
        //     'login',
        //     'register',
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
