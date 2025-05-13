<?php

namespace App\Filament\Admin\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditTeam extends EditTenantProfile
{
    protected string $view = 'filament.pages.edit-team';

    protected static ?int $navigationSort = 2;

    public static function getLabel(): string
    {
        return 'Team Settings';
    }

    #[\Override]
    protected function getViewData(): array
    {
        return [
            'team' => Filament::getTenant(),
        ];
    }
}
