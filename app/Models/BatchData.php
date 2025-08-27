<?php

namespace App\Models;

use Filament\Facades\Filament;

class BatchData extends \FamilyTree365\LaravelGedcom\Utils\BatchData
{
    #[\Override]
    public static function upsert($modelClass, $conn, array $values, array $uniqueBy, array $update = [])
    {
        // error_log("modi upsert");
        $teamId = null;

        // Only try to get tenant if we're in a web context with auth and Filament is properly initialized
        if (auth()->check() && app()->bound('filament') && \Filament\Facades\Filament::hasTenancy()) {
            try {
                $tenant = \Filament\Facades\Filament::getTenant();
                $teamId = $tenant ? $tenant->id : null;
            } catch (\Exception $e) {
                // Silently handle cases where tenant context is not available
                $teamId = null;
            }
        }

        // Add team_id to each data item
        foreach ($values as &$value) {
            $value['team_id'] = $teamId;
        }

        return parent::upsert($modelClass, $conn, $values, $uniqueBy, $update);
    }
}
