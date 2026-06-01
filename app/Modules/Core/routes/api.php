<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Core Module API Routes
|--------------------------------------------------------------------------
|
| Here are the API routes for the Core genealogy module.
|
*/

Route::middleware(['api'])->prefix('api/genealogy')->group(function (): void {
    // Core genealogy API routes can be added here
    // These would be shared API endpoints used by multiple modules
});
