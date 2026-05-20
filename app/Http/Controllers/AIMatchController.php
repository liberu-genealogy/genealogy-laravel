<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AIMatchController extends Controller
{
    /**
     * Confirm an AI match suggestion.
     */
    public function confirm(Request $request, $suggestion)
    {
        // TODO: Implement confirmation logic
        return response()->json([
            'message' => 'Match confirmed',
            'suggestion' => $suggestion,
        ]);
    }

    /**
     * Reject an AI match suggestion.
     */
    public function reject(Request $request, $suggestion)
    {
        // TODO: Implement rejection logic
        return response()->json([
            'message' => 'Match rejected',
            'suggestion' => $suggestion,
        ]);
    }
}
