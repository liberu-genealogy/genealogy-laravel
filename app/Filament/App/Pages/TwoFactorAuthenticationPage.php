<?php

namespace App\Filament\App\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Override;

class TwoFactorAuthenticationPage extends Page
{
    #[Override]
    protected string $view = 'filament.pages.profile.two-factor-authentication';

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    #[Override]
    protected static ?string $navigationLabel = 'Two-Factor Authentication';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '👤 Account & Settings';

    #[Override]
    protected static ?int $navigationSort = 4;

    #[Override]
    protected static ?string $title = 'Two Factor Authentication';

    public User $user;

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    /*
     * This page is a signpost: its view renders a static "manage two factor
     * authentication from your profile" message and calls no actions. The real
     * feature is Jetstream's profile.two-factor-authentication-form Livewire
     * component, whose own class implements enable/disable/recovery-codes.
     *
     * Four methods here forwarded to $user->enableTwoFactorAuthentication() and
     * friends, which are not User methods at all — Fortify's TwoFactorAuthenticatable
     * trait provides hasEnabledTwoFactorAuthentication()/recoveryCodes()/
     * twoFactorQrCodeSvg(), while enabling is the invokable action
     * Laravel\Fortify\Actions\EnableTwoFactorAuthentication. Nothing called them, so
     * they were dead code that would have fataled the moment a button was wired up.
     * Removed rather than reimplemented: the working path already exists.
     */
    #[Override]
    public function getHeading(): string
    {
        return static::$title;
    }

    #[Override]
    public static function shouldRegisterNavigation(): bool
    {
        return true; // config('filament-jetstream.show_two_factor_authentication_page');
    }
}
