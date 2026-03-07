<?php

namespace App\Filament\App\Resources;

use Filament\Resources\Resource;

abstract class AppResource extends Resource
{
    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function canAccess(): bool
    {
        return auth()->check();
    }
}
