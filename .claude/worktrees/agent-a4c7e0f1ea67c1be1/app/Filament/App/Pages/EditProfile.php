<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use Override;
use BackedEnum;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class EditProfile extends Page
{
    #[\Override]
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-circle';

    #[\Override]
    protected string $view = 'filament.pages.edit-profile';

    #[\Override]
    protected static ?string $navigationLabel = 'Profile';

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
