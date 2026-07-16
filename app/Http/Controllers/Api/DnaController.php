<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dna;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DnaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(Dna::latest()->paginate($request->integer('per_page', 25)));
    }

    public function show(Dna $dna): JsonResponse
    {
        return response()->json($dna);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'variable_name' => ['required', 'string', 'max:20', 'unique:dnas,variable_name'],
            'gedcom_id' => ['nullable', 'exists:gedcoms,id'],
        ]);

        return response()->json(Dna::create($data), 201);
    }

    public function destroy(Dna $dna): JsonResponse
    {
        $dna->delete();

        return response()->json(null, 204);
    }
}
