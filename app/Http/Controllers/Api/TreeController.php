<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tree;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TreeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $trees = Tree::latest()->paginate($request->integer('per_page', 25));

        return response()->json($trees);
    }

    public function show(Tree $tree): JsonResponse
    {
        return response()->json($tree);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $tree = Tree::create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($tree, 201);
    }

    public function update(Request $request, Tree $tree): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_public' => ['boolean'],
        ]);

        $tree->update($data);

        return response()->json($tree);
    }

    public function destroy(Tree $tree): JsonResponse
    {
        $tree->delete();

        return response()->json(null, 204);
    }

    public function people(Tree $tree): JsonResponse
    {
        return response()->json(['data' => [], 'tree_id' => $tree->id]);
    }

    public function families(Tree $tree): JsonResponse
    {
        return response()->json(['data' => [], 'tree_id' => $tree->id]);
    }

    public function statistics(Tree $tree): JsonResponse
    {
        return response()->json([
            'people_count' => 0,
            'families_count' => 0,
        ]);
    }
}
