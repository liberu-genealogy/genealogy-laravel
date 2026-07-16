<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Override;

class ApiTokens extends Page
{
    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-key';

    #[Override]
    protected string $view = 'filament.pages.api-tokens';

    #[Override]
    protected static ?string $navigationLabel = 'API Tokens';

    #[Override]
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    #[Override]
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
}
