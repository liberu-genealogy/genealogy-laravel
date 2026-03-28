<?php

namespace App\Models;

use Exception;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Log;

/**
 * Application-level BatchData helper.
 *
 * The vendor's FamilyTree365\LaravelGedcom\Utils\BatchData is declared final
 * and therefore cannot be extended.  This class replicates the upsert logic
 * from the vendor while injecting the current tenant's team_id so that all
 * imported records are scoped to the right team.
 */
class BatchData
{
    private const int DEFAULT_CHUNK_SIZE = 1000;

    public static function upsert(string $modelClass, string $conn, array $values, array $uniqueBy, array $update = []): bool
    {
        if (empty($values)) {
            return true;
        }

        $teamId = null;

        // Only try to get tenant in a web context where auth and Filament are available
        if (auth()->check() && app()->bound('filament') && Filament::hasTenancy()) {
            try {
                $tenant = Filament::getTenant();
                $teamId = $tenant?->id;
            } catch (Exception) {
                // Silently fall back when tenant context is unavailable (e.g. queue worker)
            }
        }

        // Inject team_id into every record so imports land in the correct team
        foreach ($values as &$value) {
            $value['team_id'] = $teamId;
        }
        unset($value);

        $chunks  = array_chunk($values, self::DEFAULT_CHUNK_SIZE);
        $success = true;

        foreach ($chunks as $chunk) {
            try {
                $result  = app($modelClass)->on($conn)->upsert($chunk, $uniqueBy, $update);
                $success = $success && ($result !== false);
            } catch (\Throwable $e) {
                Log::error('BatchData::upsert chunk failed', [
                    'model'      => $modelClass,
                    'connection' => $conn,
                    'chunk_size' => count($chunk),
                    'error'      => $e->getMessage(),
                ]);
                $success = false;
            }
        }

        return $success;
    }
}
