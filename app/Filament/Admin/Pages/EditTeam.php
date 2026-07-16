<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Tenancy\EditTenantProfile;
use Override;

class EditTeam extends EditTenantProfile
{
    #[Override]
    protected string $view = 'filament.pages.edit-team';

    #[Override]
    protected static ?int $navigationSort = 2;

    public static function getLabel(): string
    {
        return 'Team Settings';
    }

    #[Override]
    protected function getViewData(): array
    {
        return [
            'team' => Filament::getTenant(),
        ];
    }
}
