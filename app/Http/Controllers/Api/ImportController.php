<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ImportJob;
use App\Services\DnaImportService;
use App\Services\GedcomService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function gedcom(Request $request, GedcomService $service): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:ged,gedcom,txt', 'max:51200'],
            'tree_id' => ['nullable', 'exists:trees,id'],
        ]);

        $job = $service->queueImport(
            $request->file('file'),
            $request->integer('tree_id')
        );

        return response()->json($job, 202);
    }

    public function dna(Request $request, DnaImportService $service): JsonResponse
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:102400'],
            'format' => ['nullable', 'in:23andme,ancestry,ftdna'],
            'consent_given' => ['nullable', 'boolean'],
        ]);

        // importSingleKit() reads the kit off the 'private' disk by relative path
        // and detects the format itself, so store the upload first; 'format' stays
        // advisory. Matching is only dispatched for a kit whose owner consented,
        // so consent has to be asked for here rather than assumed.
        $path = $request->file('file')->store('dna-uploads', 'private');

        try {
            $result = $service->importSingleKit(
                $path,
                (int) $request->user()->id,
                true,
                (bool) ($data['consent_given'] ?? false)
            );
        } catch (Exception $e) {
            // A file that clears the mimes rule can still be unparseable as DNA.
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($result, 201);
    }

    public function status(ImportJob $job): JsonResponse
    {
        return response()->json([
            'id' => $job->id,
            'status' => $job->status,
            'progress' => $job->progress,
            'error' => $job->error_message,
            'created_at' => $job->created_at,
            'updated_at' => $job->updated_at,
        ]);
    }
}
