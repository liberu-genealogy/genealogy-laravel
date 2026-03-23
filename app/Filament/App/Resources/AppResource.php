<?php

namespace App\Filament\App\Resources;

use Filament\Resources\Resource;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

abstract class AppResource extends Resource
{
    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function canCreate(): bool
    {
        return auth()->check();
    }

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    public static function getAuthorizationResponse(string | UnitEnum $action, ?Model $record = null): Response
    {
        if (! auth()->check()) {
            return Response::deny();
        }

        return Response::allow();
    }
}
