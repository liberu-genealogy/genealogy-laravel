<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(Source::latest()->paginate($request->integer('per_page', 25)));
    }

    public function show(Source $source): JsonResponse
    {
        return response()->json($source);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'titl' => ['required', 'string', 'max:255'],
            'auth' => ['nullable', 'string', 'max:255'],
            'publ' => ['nullable', 'string'],
            'abbr' => ['nullable', 'string', 'max:100'],
        ]);

        return response()->json(Source::create($data), 201);
    }

    public function update(Request $request, Source $source): JsonResponse
    {
        $data = $request->validate([
            'titl' => ['sometimes', 'string', 'max:255'],
            'auth' => ['nullable', 'string', 'max:255'],
            'publ' => ['nullable', 'string'],
            'abbr' => ['nullable', 'string', 'max:100'],
        ]);

        $source->update($data);

        return response()->json($source);
    }

    public function destroy(Source $source): JsonResponse
    {
        $source->delete();

        return response()->json(null, 204);
    }
}
