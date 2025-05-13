<?php

namespace App\Filament\Admin\Pages;

use BackedEnum;
use Filament\Pages\Page;

class EditProfile extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user-circle';

    protected string $view = 'filament.pages.edit-profile';

    protected static ?string $navigationLabel = 'Profile';

    #[\Override]
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    #[\Override]
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
}
