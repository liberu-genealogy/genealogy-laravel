<?php

declare(strict_types=1);

use App\Http\Controllers\Api\DnaController;
use App\Http\Controllers\Api\FamilyController;
use App\Http\Controllers\Api\ImportController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\SourceController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\TreeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json(['status' => 'ok', 'timestamp' => now()->toISOString()]));

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());

    // Core genealogy resources
    Route::apiResource('people', PersonController::class);
    Route::apiResource('families', FamilyController::class);
    Route::apiResource('trees', TreeController::class);
    Route::apiResource('notes', NoteController::class);
    Route::apiResource('places', PlaceController::class);
    Route::apiResource('sources', SourceController::class);
    Route::apiResource('media', MediaController::class);
    Route::apiResource('dna', DnaController::class)->only(['index', 'show', 'store', 'destroy']);

    // Person sub-resources
    Route::prefix('people/{person}')->group(function () {
        Route::get('events', [PersonController::class, 'events']);
        Route::get('families', [PersonController::class, 'families']);
        Route::get('media', [PersonController::class, 'media']);
        Route::get('notes', [PersonController::class, 'notes']);
    });

    // Family sub-resources
    Route::prefix('families/{family}')->group(function () {
        Route::get('children', [FamilyController::class, 'children']);
        Route::get('events', [FamilyController::class, 'events']);
    });

    // Tree sub-resources
    Route::prefix('trees/{tree}')->group(function () {
        Route::get('people', [TreeController::class, 'people']);
        Route::get('families', [TreeController::class, 'families']);
        Route::get('statistics', [TreeController::class, 'statistics']);
    });

    // Import
    Route::post('import/gedcom', [ImportController::class, 'gedcom']);
    Route::post('import/dna', [ImportController::class, 'dna']);
    Route::get('import/{job}', [ImportController::class, 'status']);

    // Teams
    Route::apiResource('teams', TeamController::class);
});
