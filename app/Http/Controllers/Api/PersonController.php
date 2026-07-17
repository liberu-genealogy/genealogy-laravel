<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PersonController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Person::query();

        if ($search = $request->string('search')) {
            $query->where(fn ($q) => $q
                ->where('givn', 'like', "%{$search}%")
                ->orWhere('surn', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
            );
        }

        if ($sex = $request->string('sex')) {
            $query->where('sex', $sex->upper());
        }

        return PersonResource::collection(
            $query->latest()->paginate($request->integer('per_page', 25))
        );
    }

    public function show(Person $person): JsonResponse
    {
        return response()->json([
            'data' => $person,
            'families' => $person->families(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'givn' => ['nullable', 'string', 'max:255'],
            'surn' => ['nullable', 'string', 'max:255'],
            'sex' => ['required', 'in:M,F,U'],
            'name' => ['nullable', 'string', 'max:255'],
            'birthday' => ['nullable', 'date'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        $person = Person::create($data);

        return response()->json($person, 201);
    }

    public function update(Request $request, Person $person): JsonResponse
    {
        $data = $request->validate([
            'givn' => ['nullable', 'string', 'max:255'],
            'surn' => ['nullable', 'string', 'max:255'],
            'sex' => ['nullable', 'in:M,F,U'],
            'name' => ['nullable', 'string', 'max:255'],
            'birthday' => ['nullable', 'date'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        $person->update($data);

        return response()->json($person);
    }

    public function destroy(Person $person): JsonResponse
    {
        $person->delete();

        return response()->json(null, 204);
    }

    public function events(Person $person): JsonResponse
    {
        return response()->json($person->events()->get());
    }

    public function families(Person $person): JsonResponse
    {
        return response()->json($person->families());
    }

    public function media(Person $person): JsonResponse
    {
        return response()->json($person->photos()->get());
    }

    public function notes(Person $person): JsonResponse
    {
        return response()->json([]);
    }
}
