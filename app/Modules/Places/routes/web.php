<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Places\Http\Controllers\PlacesController;

/*
|--------------------------------------------------------------------------
| Places Module Web Routes
|--------------------------------------------------------------------------
|
| Here are the web routes for the Places module.
|
*/

Route::middleware(['web'])->prefix('places')->name('places.')->group(function () {
    Route::get('/', [PlacesController::class, 'index'])->name('index');
    Route::get('/create', [PlacesController::class, 'create'])->name('create');
    Route::post('/', [PlacesController::class, 'store'])->name('store');
    Route::get('/{place}', [PlacesController::class, 'show'])->name('show');
    Route::get('/{place}/edit', [PlacesController::class, 'edit'])->name('edit');
    Route::put('/{place}', [PlacesController::class, 'update'])->name('update');
    Route::delete('/{place}', [PlacesController::class, 'destroy'])->name('destroy');
    
    // Places-specific routes
    Route::get('/search/{query}', [PlacesController::class, 'search'])->name('search');
    Route::get('/country/{country}', [PlacesController::class, 'byCountry'])->name('by-country');
    Route::post('/{place}/geocode', [PlacesController::class, 'geocode'])->name('geocode');
    Route::get('/map/view', [PlacesController::class, 'mapView'])->name('map');
});