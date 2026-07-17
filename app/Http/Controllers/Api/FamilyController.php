<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Family;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $families = Family::latest()->paginate($request->integer('per_page', 25));

        return response()->json($families);
    }

    public function show(Family $family): JsonResponse
    {
        $family->load(['husband', 'wife', 'children', 'events']);

        return response()->json($family);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'husb' => ['nullable', 'exists:people,id'],
            'wife' => ['nullable', 'exists:people,id'],
            'tree_id' => ['nullable', 'exists:trees,id'],
        ]);

        $family = Family::create($data);

        return response()->json($family, 201);
    }

    public function update(Request $request, Family $family): JsonResponse
    {
        $data = $request->validate([
            'husb' => ['nullable', 'exists:people,id'],
            'wife' => ['nullable', 'exists:people,id'],
            'tree_id' => ['nullable', 'exists:trees,id'],
        ]);

        $family->update($data);

        return response()->json($family);
    }

    public function destroy(Family $family): JsonResponse
    {
        $family->delete();

        return response()->json(null, 204);
    }

    public function children(Family $family): JsonResponse
    {
        return response()->json($family->children()->get());
    }

    public function events(Family $family): JsonResponse
    {
        return response()->json($family->events()->get());
    }
}
