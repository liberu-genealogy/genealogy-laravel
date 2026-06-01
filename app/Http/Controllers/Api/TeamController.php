<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teams = $request->user()->allTeams()->values();

        return response()->json($teams);
    }

    public function show(Team $team): JsonResponse
    {
        $this->authorize('view', $team);

        return response()->json($team->load('members'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'is_public' => ['boolean'],
        ]);

        $team = Team::forceCreate([
            'user_id'       => $request->user()->id,
            'name'          => $data['name'],
            'personal_team' => false,
        ]);

        return response()->json($team, 201);
    }

    public function update(Request $request, Team $team): JsonResponse
    {
        $this->authorize('update', $team);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $team->update($data);

        return response()->json($team);
    }

    public function destroy(Team $team): JsonResponse
    {
        $this->authorize('delete', $team);

        $team->delete();

        return response()->json(null, 204);
    }
}
