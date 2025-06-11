<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Person\Http\Controllers\PersonController;

/*
|--------------------------------------------------------------------------
| Person Module Web Routes
|--------------------------------------------------------------------------
|
| Here are the web routes for the Person module.
|
*/

Route::middleware(['web'])->prefix('persons')->name('persons.')->group(function () {
    Route::get('/', [PersonController::class, 'index'])->name('index');
    Route::get('/create', [PersonController::class, 'create'])->name('create');
    Route::post('/', [PersonController::class, 'store'])->name('store');
    Route::get('/{person}', [PersonController::class, 'show'])->name('show');
    Route::get('/{person}/edit', [PersonController::class, 'edit'])->name('edit');
    Route::put('/{person}', [PersonController::class, 'update'])->name('update');
    Route::delete('/{person}', [PersonController::class, 'destroy'])->name('destroy');
    
    // Additional person routes
    Route::get('/{person}/timeline', [PersonController::class, 'timeline'])->name('timeline');
    Route::get('/{person}/tree', [PersonController::class, 'tree'])->name('tree');
    Route::post('/{person}/events', [PersonController::class, 'addEvent'])->name('add-event');
});