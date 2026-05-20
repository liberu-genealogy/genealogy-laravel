<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Person\Http\Controllers\Api\PersonApiController;

/*
|--------------------------------------------------------------------------
| Person Module API Routes
|--------------------------------------------------------------------------
|
| Here are the API routes for the Person module.
|
*/

Route::middleware(['api'])->prefix('api/persons')->name('api.persons.')->group(function () {
    Route::get('/', [PersonApiController::class, 'index'])->name('index');
    Route::post('/', [PersonApiController::class, 'store'])->name('store');
    Route::get('/{person}', [PersonApiController::class, 'show'])->name('show');
    Route::put('/{person}', [PersonApiController::class, 'update'])->name('update');
    Route::delete('/{person}', [PersonApiController::class, 'destroy'])->name('destroy');
    
    // Search and statistics
    Route::get('/search/{query}', [PersonApiController::class, 'search'])->name('search');
    Route::get('/statistics/overview', [PersonApiController::class, 'statistics'])->name('statistics');
    
    // Relationships
    Route::get('/{person}/ancestors', [PersonApiController::class, 'ancestors'])->name('ancestors');
    Route::get('/{person}/descendants', [PersonApiController::class, 'descendants'])->name('descendants');
    Route::get('/{person}/siblings', [PersonApiController::class, 'siblings'])->name('siblings');
    
    // Events
    Route::get('/{person}/events', [PersonApiController::class, 'events'])->name('events');
    Route::post('/{person}/events', [PersonApiController::class, 'addEvent'])->name('add-event');
    
    // Export
    Route::get('/{person}/export', [PersonApiController::class, 'export'])->name('export');
});