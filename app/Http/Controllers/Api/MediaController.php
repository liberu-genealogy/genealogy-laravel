<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MediaObject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(MediaObject::latest()->paginate($request->integer('per_page', 25)));
    }

    public function show(MediaObject $medium): JsonResponse
    {
        return response()->json($medium);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'titl' => ['nullable', 'string', 'max:255'],
            'file' => ['required', 'string', 'max:500'],
            'form' => ['nullable', 'string', 'max:50'],
            'type' => ['nullable', 'string', 'max:50'],
        ]);

        return response()->json(MediaObject::create($data), 201);
    }

    public function update(Request $request, MediaObject $medium): JsonResponse
    {
        $data = $request->validate([
            'titl' => ['nullable', 'string', 'max:255'],
            'file' => ['sometimes', 'string', 'max:500'],
            'form' => ['nullable', 'string', 'max:50'],
            'type' => ['nullable', 'string', 'max:50'],
        ]);

        $medium->update($data);

        return response()->json($medium);
    }

    public function destroy(MediaObject $medium): JsonResponse
    {
        $medium->delete();

        return response()->json(null, 204);
    }
}
