<?php

namespace App\Filament\App\Pages;

use Override;
use UnitEnum;
use BackedEnum;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthenticationPage extends Page
{
    protected string $view = 'filament.pages.profile.two-factor-authentication';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-key';

    protected static string | \UnitEnum | null $navigationGroup = 'Account';

    protected static ?int $navigationSort = 4;

    protected static ?string $title = 'Two Factor Authentication';

    public User $user;

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    public function enableTwoFactorAuthentication(): void
    {
        $this->user->enableTwoFactorAuthentication();
    }

    public function disableTwoFactorAuthentication(): void
    {
        $this->user->disableTwoFactorAuthentication();
    }

    public function showRecoveryCodes(): void
    {
        $this->user->showRecoveryCodes();
    }

    public function regenerateRecoveryCodes(): void
    {
        $this->user->regenerateRecoveryCodes();
    }

    #[Override]
    public function getHeading(): string
    {
        return static::$title;
    }

    #[Override]
    public static function shouldRegisterNavigation(): bool
    {
        return true; //config('filament-jetstream.show_two_factor_authentication_page');
    }
}
