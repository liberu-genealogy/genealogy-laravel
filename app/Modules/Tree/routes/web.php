<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Tree\Http\Controllers\TreeController;

/*
|--------------------------------------------------------------------------
| Tree Module Web Routes
|--------------------------------------------------------------------------
|
| Here are the web routes for the Tree module.
|
*/

Route::middleware(['web'])->prefix('tree')->name('tree.')->group(function () {
    Route::get('/', [TreeController::class, 'index'])->name('index');
    Route::get('/person/{person}', [TreeController::class, 'person'])->name('person');
    Route::get('/pedigree/{person}', [TreeController::class, 'pedigree'])->name('pedigree');
    Route::get('/descendants/{person}', [TreeController::class, 'descendants'])->name('descendants');
    Route::get('/interactive/{person}', [TreeController::class, 'interactive'])->name('interactive');
    
    // Tree export routes
    Route::get('/export/{person}/pdf', [TreeController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/{person}/svg', [TreeController::class, 'exportSvg'])->name('export.svg');
    Route::get('/export/{person}/png', [TreeController::class, 'exportPng'])->name('export.png');
});