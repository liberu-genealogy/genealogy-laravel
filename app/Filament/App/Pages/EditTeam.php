<?php

namespace App\Filament\App\Pages;

use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditTeam extends EditTenantProfile
{
    protected static string $view = 'filament.pages.edit-team';

    public static function getLabel(): string
    {
        return 'Edit Team';
    }

    protected function getViewData(): array
    {
        return [
            'team' => Filament::getTenant(),
        ];
    }
}
