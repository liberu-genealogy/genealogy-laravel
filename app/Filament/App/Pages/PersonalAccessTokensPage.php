<?php

namespace App\Filament\App\Pages;

use Override;
use UnitEnum;
use BackedEnum;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PersonalAccessTokensPage extends Page
{
    protected string $view = 'filament.pages.profile.personal-access-tokens';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-key';

    protected static string | \UnitEnum | null $navigationGroup = 'Account';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Personal Access Tokens';

    public User $user;

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    public function createApiToken(string $name): void
    {
        $this->user->createToken($name);
    }

    public function deleteApiToken(string $name): void
    {
        $this->user->tokens()->where('name', $name)->first()->delete();
    }

    #[Override]
    public function getHeading(): string
    {
        return static::$title;
    }

    #[Override]
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
