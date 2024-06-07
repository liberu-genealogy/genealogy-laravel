<?php

namespace App\Models;

use Filament\Facades\Filament;

class BatchData extends \FamilyTree365\LaravelGedcom\Utils\BatchData
{
    public static function upsert($modelClass, $conn, array $values, array $uniqueBy, array $update = [])
    {
        // error_log("modi upsert");
        $teamId = auth()->check() ? Filament::getTenant()->id : null;

        // Add team_id to each data item
        foreach ($values as &$value) {
            $value['team_id'] = $teamId;
        }

        return parent::upsert($modelClass, $conn, $values, $uniqueBy, $update);
    }
}
