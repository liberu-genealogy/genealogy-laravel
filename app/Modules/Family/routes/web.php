<?php

declare(strict_types=1);

use App\Modules\Family\Http\Controllers\FamilyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Family Module Web Routes
|--------------------------------------------------------------------------
|
| Here are the web routes for the Family module.
|
*/

// Name prefix is module-scoped: 'families.' collided with routes/api.php's
// apiResource('families'), which claims the same families.index/store/show/... names.
// Duplicate route names are only fatal when routes are cached, so this never
// surfaced in dev and made `php artisan route:cache` — and therefore the whole
// production image — fail to boot. Nothing referenced these names by route().
Route::middleware(['web'])->prefix('families')->name('modules.families.')->group(function (): void {
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
