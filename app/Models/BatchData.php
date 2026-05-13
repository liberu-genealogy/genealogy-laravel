<?php

namespace App\Models;

use Override;
use Exception;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Log;

class BatchData 
{
    #[Override]
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
                $teamId = $tenant ? $tenant->id : null;
                $teamId = $tenant?->id;
            } catch (Exception) {
                // Silently fall back when tenant context is unavailable (e.g. queue worker)
            }
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
        
