<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AISuggestedMatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AIMatchController extends Controller
{
    /**
     * Confirm an AI match suggestion.
     */
    public function confirm(Request $request, AISuggestedMatch $suggestion): JsonResponse
    {
        return $this->setStatus($request, $suggestion, 'confirmed');
    }

    /**
     * Reject an AI match suggestion.
     */
    public function reject(Request $request, AISuggestedMatch $suggestion): JsonResponse
    {
        return $this->setStatus($request, $suggestion, 'rejected');
    }

    // ponytail: AISuggestedMatch has no BelongsToTenant global scope, so tenancy
    // is enforced here by matching the row's team_id to the caller's current team.
    private function setStatus(Request $request, AISuggestedMatch $suggestion, string $status): JsonResponse
    {
        abort_unless($suggestion->team_id === $request->user()->current_team_id, 403);

        $suggestion->update(['status' => $status]);

        return response()->json([
            'message' => "Match {$status}",
            'suggestion' => $suggestion->id,
        ]);
    }
}
