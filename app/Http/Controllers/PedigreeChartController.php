<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

final readonly class PedigreeChartController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Pedigree Chart functionality is under development.',
            'status' => 'pending'
        ]);
    }
}