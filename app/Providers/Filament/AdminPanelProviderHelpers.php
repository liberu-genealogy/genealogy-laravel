<?php

namespace App\Providers\Filament;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Pages\Tenancy\EditTeamProfile;
use App\Filament\Pages\Tenancy\RegisterTeam;
use App\Models\Team;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Maartenpaauw\Filament\Cashier\Stripe\BillingProvider;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Pages;
use App\Providers\Filament\SyncSpatiePermissionsWithFilamentTenants;

class AdminPanelProviderHelpers
{
    public static function setDefaultPanel(Panel $panel): Panel
    {
        return $panel->default();
    }

    public static function setId(Panel $panel, string $id): Panel
    {
        return $panel->id($id);
    }

    public static function setPath(Panel $panel, string $path): Panel
    {
        return $panel->path($path);
    }

    public static function enableLogin(Panel $panel): Panel
    {
        return $panel->login();
    }

    public static function enableRegistration(Panel $panel): Panel
    {
        return $panel->registration();
    }

    public static function enablePasswordReset(Panel $panel): Panel
    {
        return $panel->passwordReset();
    }

    public static function enableEmailVerification(Panel $panel): Panel
    {
        return $panel->emailVerification();
    }

    public static function enableProfile(Panel $panel): Panel
    {
        return $panel->profile();
    }

    public static function setColors(Panel $panel, array $colors): Panel
    {
        return $panel->colors($colors);
    }

    public static function discoverResources(Panel $panel, string $path, string $namespace): Panel
    {
        return $panel->discoverResources($path, $namespace);
    }

    public static function discoverPages(Panel $panel, string $path, string $namespace): Panel
    {
        return $panel->discoverPages($path, $namespace);
    }

    public static function setPages(Panel $panel, array $pages): Panel
    {
        return $panel->pages($pages);
    }

    public static function discoverWidgets(Panel $panel, string $path, string $namespace): Panel
    {
        return $panel->discoverWidgets($path, $namespace);
    }

    public static function setWidgets(Panel $panel, array $widgets): Panel
    {
        return $panel->widgets($widgets);
    }

    public static function setPlugin(Panel $panel, FilamentSpatieRolesPermissionsPlugin $plugin): Panel
    {
        return $panel->plugin($plugin);
    }

    public static function setTenantRegistration(Panel $panel, string $registrationPage): Panel
    {
        return $panel->tenantRegistration($registrationPage);
    }

    public static function setTenantProfile(Panel $panel, string $profilePage): Panel
    {
        return $panel->tenantProfile($profilePage);
    }

    public static function setTenant(Panel $panel, string $tenantClass): Panel
    {
        return $panel->tenant($tenantClass);
    }

    public static function setTenantBillingProvider(Panel $panel, BillingProvider $billingProvider): Panel
    {
        return $panel->tenantBillingProvider($billingProvider);
    }

    public static function requiresTenantSubscription(Panel $panel): Panel
    {
        return $panel->requiresTenantSubscription();
    }

    public static function setTenantMiddleware(Panel $panel, array $middleware, bool $isPersistent): Panel
    {
        return $panel->tenantMiddleware($middleware, $isPersistent);
    }

    public static function setMiddleware(Panel $panel, array $middleware): Panel
    {
        return $panel->middleware($middleware);
    }

    public static function setAuthMiddleware(Panel $panel, array $authMiddleware): Panel
    {
        return $panel->authMiddleware($authMiddleware);
    }
}
