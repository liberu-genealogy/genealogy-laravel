<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/*
 * Two stacked faults made /pedigree-chart return 500 for everyone, and broke
 * `php artisan route:list` for every developer:
 *
 * 1. The class was `final readonly` and extends a non-readonly Controller, which
 *    PHP 8.2+ rejects outright — a fatal at class load, before routing. It had
 *    no properties, so `readonly` bought nothing anyway.
 * 2. routes/web.php binds this to `show`, but the only method was `index`, so it
 *    would have thrown BadMethodCallException even once it loaded.
 *
 * Both arrived in the Laravel 13 / PHP 8.5 upgrade (06771ab1). FanChartController
 * is the working sibling: plain `class`, method named `show`.
 */
final class PedigreeChartController extends Controller
{
    public function show(): JsonResponse
    {
        return response()->json([
            'message' => 'Pedigree Chart functionality is under development.',
            'status' => 'pending'
        ]);
    }
}