<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Events\Http\Controllers\EventsController;

/*
|--------------------------------------------------------------------------
| Events Module Web Routes
|--------------------------------------------------------------------------
|
| Here are the web routes for the Events module.
|
*/

Route::middleware(['web'])->prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventsController::class, 'index'])->name('index');
    Route::get('/create', [EventsController::class, 'create'])->name('create');
    Route::post('/', [EventsController::class, 'store'])->name('store');
    Route::get('/{event}', [EventsController::class, 'show'])->name('show');
    Route::get('/{event}/edit', [EventsController::class, 'edit'])->name('edit');
    Route::put('/{event}', [EventsController::class, 'update'])->name('update');
    Route::delete('/{event}', [EventsController::class, 'destroy'])->name('destroy');
    
    // Events-specific routes
    Route::get('/type/{type}', [EventsController::class, 'byType'])->name('by-type');
    Route::get('/timeline/view', [EventsController::class, 'timeline'])->name('timeline');
    Route::get('/calendar/view', [EventsController::class, 'calendar'])->name('calendar');
    Route::get('/search/{query}', [EventsController::class, 'search'])->name('search');
});