<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(Note::latest()->paginate($request->integer('per_page', 25)));
    }

    public function show(Note $note): JsonResponse
    {
        return response()->json($note);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'note' => ['required', 'string'],
            'type' => ['nullable', 'string', 'max:50'],
        ]);

        return response()->json(Note::create($data), 201);
    }

    public function update(Request $request, Note $note): JsonResponse
    {
        $data = $request->validate([
            'note' => ['sometimes', 'string'],
            'type' => ['nullable', 'string', 'max:50'],
        ]);

        $note->update($data);

        return response()->json($note);
    }

    public function destroy(Note $note): JsonResponse
    {
        $note->delete();

        return response()->json(null, 204);
    }
}
