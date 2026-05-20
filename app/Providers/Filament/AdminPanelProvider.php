<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\CreateTeam;
use App\Filament\Admin\Pages\EditProfile;
use App\Filament\Admin\Pages\EditTeam;
use App\Http\Middleware\TeamsPermission;
use App\Models\Team;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages as FilamentPage;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Jetstream;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            ->id('admin')
            ->path('admin')
            ->login([AuthenticatedSessionController::class, 'create'])
            ->passwordReset()
            ->emailVerification()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName(fn () => app(\App\Settings\GeneralSettings::class)->site_name)
            ->colors([
                'primary' => Color::Emerald,
                'gray' => Color::Slate,
            ])
            ->brandName(fn () => app(\App\Settings\GeneralSettings::class)->site_name)
            ->brandLogo(asset('images/logo.svg'))
            ->favicon(asset('images/favicon.ico'))
            ->userMenuItems([
                Action::make('profile')
                    ->label('Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url(fn () => $this->shouldRegisterMenuItem()
                        ? url(EditProfile::getUrl())
                        : url($panel->getPath())),
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                FilamentPage\Dashboard::class,
                EditProfile::class
                // Pages\ApiTokenManagerPage::class,
            ])->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                TeamsPermission::class,
                // \App\Http\Middleware\EnsureUserHasAdminRole::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('Administration')
            ]);

        // if (Features::hasApiFeatures()) {
        //     $panel->userMenuItems([
        //         MenuItem::make()
        //             ->label('API Tokens')
        //             ->icon('heroicon-o-key')
        //             ->url(fn () => $this->shouldRegisterMenuItem()
        //                 ? url(Pages\ApiTokenManagerPage::getUrl())
        //                 : url($panel->getPath())),
        //     ]);
        // }

        // if (Features::hasTeamFeatures()) {
        //     $panel
        //         ->tenant(Team::class, ownershipRelationship: 'team')
        //         ->tenantRegistration(CreateTeam::class)
        //         ->tenantProfile(EditTeam::class)
        //         ->userMenuItems([
        //             Action::make('team-settings')
        //                 ->label('Team Settings')
        //                 ->icon('heroicon-o-cog-6-tooth')
        //                 ->url(fn () => $this->shouldRegisterMenuItem()
        //                     ? url(EditTeam::getUrl())
        //                     : url($panel->getPath())),
        //         ]);
        // }

        return $panel;
    }

    public function boot(): void
    {
        /**
         * Disable Fortify routes.
         */
        Fortify::$registersRoutes = false;

        /**
         * Disable Jetstream routes.
         */
        Jetstream::$registersRoutes = false;
    }

    public function shouldRegisterMenuItem(): bool
    {
        $hasVerifiedEmail = !is_null(auth()->user());//?->hasVerifiedEmail();

        // Check if Filament is properly initialized before using facades
        if (!app()->bound('filament')) {
            return $hasVerifiedEmail;
        }

        try {
            return Filament::hasTenancy()
                ? $hasVerifiedEmail && Filament::getTenant()
                : $hasVerifiedEmail;
        } catch (\Exception $e) {
            // Fallback if facade is not ready
            return $hasVerifiedEmail;
        }
    }
}
