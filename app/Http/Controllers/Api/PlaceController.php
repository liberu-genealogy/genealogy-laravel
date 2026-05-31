<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Place::query();

        if ($search = $request->string('search')) {
            $query->where('place', 'like', "%{$search}%");
        }

        return response()->json($query->latest()->paginate($request->integer('per_page', 25)));
    }

    public function show(Place $place): JsonResponse
    {
        return response()->json($place);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'place' => ['required', 'string', 'max:255'],
            'lati'  => ['nullable', 'numeric', 'between:-90,90'],
            'long'  => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        return response()->json(Place::create($data), 201);
    }

    public function update(Request $request, Place $place): JsonResponse
    {
        $data = $request->validate([
            'place' => ['sometimes', 'string', 'max:255'],
            'lati'  => ['nullable', 'numeric', 'between:-90,90'],
            'long'  => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $place->update($data);

        return response()->json($place);
    }

    public function destroy(Place $place): JsonResponse
    {
        $place->delete();

        return response()->json(null, 204);
    }
}
