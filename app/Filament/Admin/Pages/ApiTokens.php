<?php

namespace App\Filament\Admin\Pages;

use BackedEnum;
use Filament\Pages\Page;

class ApiTokens extends Page
{
    protected static BackedEnum|string|null $navigationIcon = "heroicon-o-key";

    protected string $view = 'filament.pages.api-tokens';

    protected static ?string $navigationLabel = 'API Tokens';

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
