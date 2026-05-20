<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Family\Http\Controllers\FamilyController;

/*
|--------------------------------------------------------------------------
| Family Module Web Routes
|--------------------------------------------------------------------------
|
| Here are the web routes for the Family module.
|
*/

Route::middleware(['web'])->prefix('families')->name('families.')->group(function () {
    Route::get('/', [FamilyController::class, 'index'])->name('index');
    Route::get('/create', [FamilyController::class, 'create'])->name('create');
    Route::post('/', [FamilyController::class, 'store'])->name('store');
    Route::get('/{family}', [FamilyController::class, 'show'])->name('show');
    Route::get('/{family}/edit', [FamilyController::class, 'edit'])->name('edit');
    Route::put('/{family}', [FamilyController::class, 'update'])->name('update');
    Route::delete('/{family}', [FamilyController::class, 'destroy'])->name('destroy');
    
    // Family-specific routes
    Route::get('/{family}/tree', [FamilyController::class, 'tree'])->name('tree');
    Route::post('/{family}/children', [FamilyController::class, 'addChild'])->name('add-child');
    Route::delete('/{family}/children/{person}', [FamilyController::class, 'removeChild'])->name('remove-child');
});