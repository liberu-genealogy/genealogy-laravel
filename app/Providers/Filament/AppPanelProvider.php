<?php

namespace App\Providers\Filament;

use App\Filament\App\Pages;
use App\Filament\App\Pages\DescendantChartPage;
use App\Filament\App\Pages\EditProfile;
use App\Filament\App\Pages\FanChartPage;
use App\Filament\App\Pages\GamificationPage;
use App\Filament\App\Pages\PedigreeChartPage;
use App\Filament\App\Pages\PremiumDashboardPage;
use App\Filament\App\Pages\SubscriptionPage;
use App\Filament\App\Pages\TrialExpiredPage;
use App\Filament\App\Resources\AddrResource;
use App\Filament\App\Resources\AIRecordMatchResource;
use App\Filament\App\Resources\AuthorResource;
use App\Filament\App\Resources\ChanResource;
use App\Filament\App\Resources\ChecklistTemplateResource;
use App\Filament\App\Resources\CitationResource;
use App\Filament\App\Resources\DatabaseResource;
use App\Filament\App\Resources\DnaMatchingResource;
use App\Filament\App\Resources\DnaResource;
use App\Filament\App\Resources\DuplicateCheckResource;
use App\Filament\App\Resources\FamilyEventResource;
use App\Filament\App\Resources\FamilyResource;
use App\Filament\App\Resources\FamilySlgsResource;
use App\Filament\App\Resources\GedcomResource;
use App\Filament\App\Resources\MediaObjectResource;
use App\Filament\App\Resources\NoteResource;
use App\Filament\App\Resources\PersonAliaResource;
use App\Filament\App\Resources\PersonAnciResource;
use App\Filament\App\Resources\PersonAssoResource;
use App\Filament\App\Resources\PersonEventResource;
use App\Filament\App\Resources\PersonLdsResource;
use App\Filament\App\Resources\PersonNameFoneResource;
use App\Filament\App\Resources\PersonNameResource;
use App\Filament\App\Resources\PersonNameRomnResource;
use App\Filament\App\Resources\PersonResource;
use App\Filament\App\Resources\PersonSubmResource;
use App\Filament\App\Resources\PlaceResource;
use App\Filament\App\Resources\PublicationResource;
use App\Filament\App\Resources\RecordTypeResource;
use App\Filament\App\Resources\RefnResource;
use App\Filament\App\Resources\RepositoryResource;
use App\Filament\App\Resources\ResearchSpaceResource;
use App\Filament\App\Resources\SmartMatchResource;
use App\Filament\App\Resources\SourceDataEvenResource;
use App\Filament\App\Resources\SourceDataResource;
use App\Filament\App\Resources\SourceRefEvenResource;
// Person-merge UI. Lives under app/Modules/* (autoloadable via App\ PSR-4) and is
// registered explicitly rather than by discovering app/Modules, because a broad
// discover would also pick up the module PersonResource/PlaceResource/SourceResource/
// TypeResource that duplicate the App-panel resources above (double nav / conflicts).
use App\Filament\App\Resources\SourceRefResource;
use App\Filament\App\Resources\SourceRepoResource;
use App\Filament\App\Resources\SourceResource;
use App\Filament\App\Resources\SubmResource;
use App\Filament\App\Resources\SubnResource;
use App\Filament\App\Resources\TypeResource;
use App\Filament\App\Resources\VirtualEventResource;
use App\Http\Middleware\TeamsPermission;
use App\Listeners\CreatePersonalTeam;
use App\Listeners\SwitchTeam;
use App\Models\Team;
use App\Modules\Person\Filament\Resources\DuplicateMatchResource;
use App\Settings\GeneralSettings;
use Filament\Actions\Action;
use Filament\Auth\Events\Registered;
use Filament\Events\TenantSet;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Event;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use JoelButcher\Socialstream\Filament\SocialstreamPlugin;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Jetstream;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login([AuthenticatedSessionController::class, 'create'])
            // Symmetric with login. Without the action, Filament renders its own
            // built-in register page, so /register looked nothing like /login and
            // auth/register.blade.php was orphaned. Safe for tenancy:
            // App\Actions\Fortify\CreateNewUser creates the personal team,
            // switches to it, sets the permissions team id and assigns
            // panel_user — it does not rely on Filament's Registered event.
            ->registration([RegisteredUserController::class, 'create'])
            ->passwordReset()
            // DESIGN.md is light-only. Filament ships dark mode on and follows
            // the OS, so the panel was serving a theme with no defined palette
            // and no contrast verification.
            ->darkMode(false)
            ->emailVerification()
            ->plugin(new SocialstreamPlugin)
            ->viteTheme('resources/css/filament/app/theme.css')
            ->colors([
                'primary' => Color::Emerald,
                'gray' => Color::Slate,
            ])
            ->brandName(fn () => app(GeneralSettings::class)->site_name)
            ->brandLogo(asset('build/images/logo.svg')) // vite-plugin-static-copy writes to build/images/; asset('images/..') was 404 on every panel page
            ->favicon(asset('favicon.ico')) // public/favicon.ico is the only .ico that exists; images/favicon.ico was 404
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('🏠 Dashboard'),
                NavigationGroup::make()
                    ->label('👥 Family Tree'),
                NavigationGroup::make()
                    ->label('📊 Charts & Visualizations'),
                NavigationGroup::make()
                    ->label('📄 Reports'),
                NavigationGroup::make()
                    ->label('🔍 Research & Analysis'),
                NavigationGroup::make()
                    ->label('📋 Research Management'),
                NavigationGroup::make()
                    ->label('🧬 DNA & Genetics'),
                NavigationGroup::make()
                    ->label('📁 Media & Documents'),
                NavigationGroup::make()
                    ->label('🛠️ Data Management'),
                NavigationGroup::make()
                    ->label('👥 Family Reunions'),
                NavigationGroup::make()
                    ->label('🎮 Gamification'),
                NavigationGroup::make()
                    ->label('⚙️ System Settings'),
                NavigationGroup::make()
                    ->label('👤 Account & Settings'),
            ])
            ->userMenuItems([
                Action::make('profile')
                    ->label('Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url(fn (): UrlGenerator|string => $this->shouldRegisterMenuItem()
                        ? url(EditProfile::getUrl())
                        : url($panel->getPath())),
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->resources([
                AIRecordMatchResource::class,
                AddrResource::class,
                AuthorResource::class,
                ChanResource::class,
                ChecklistTemplateResource::class,
                CitationResource::class,
                DatabaseResource::class,
                DnaMatchingResource::class,
                DnaResource::class,
                DuplicateCheckResource::class,
                DuplicateMatchResource::class,
                FamilyEventResource::class,
                FamilyResource::class,
                FamilySlgsResource::class,
                GedcomResource::class,
                MediaObjectResource::class,
                NoteResource::class,
                PersonAliaResource::class,
                PersonAnciResource::class,
                PersonAssoResource::class,
                PersonEventResource::class,
                PersonLdsResource::class,
                PersonNameFoneResource::class,
                PersonNameResource::class,
                PersonNameRomnResource::class,
                PersonResource::class,
                PersonSubmResource::class,
                PlaceResource::class,
                PublicationResource::class,
                RecordTypeResource::class,
                RefnResource::class,
                RepositoryResource::class,
                ResearchSpaceResource::class,
                SmartMatchResource::class,
                SourceDataEvenResource::class,
                SourceDataResource::class,
                SourceRefEvenResource::class,
                SourceRefResource::class,
                SourceRepoResource::class,
                SourceResource::class,
                SubmResource::class,
                SubnResource::class,
                TypeResource::class,
                VirtualEventResource::class,
            ])
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Pages\Dashboard::class,
                PedigreeChartPage::class,
                FanChartPage::class,
                DescendantChartPage::class,
                GamificationPage::class,
                SubscriptionPage::class,
                PremiumDashboardPage::class,
                TrialExpiredPage::class,
                EditProfile::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
                // Stays in the auth group and resolves the tenant from the route
                // itself, rather than being moved to the tenant group where it
                // would run after IdentifyTenant.
                //
                // The reason is not Octane state: Spatie registers an
                // OperationTerminated listener that nulls the permission team
                // after every operation, so nothing survives between requests.
                // An earlier version of this comment claimed otherwise.
                //
                // It is that tenant middleware does not run on the authenticated
                // routes outside the tenant group — team creation, logout,
                // profile, email verification — which would leave the permission
                // team unset there. Registering in both groups does not work:
                // Laravel de-duplicates middleware, so only the first occurrence
                // runs, which silently reinstates the bug.
                TeamsPermission::class,
            ], isPersistent: true);

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

        if (Features::hasTeamFeatures()) {
            $panel
                ->tenant(Team::class, ownershipRelationship: 'team')
                ->tenantRegistration(Pages\CreateTeam::class)
                ->tenantProfile(Pages\EditTeam::class)
                ->userMenuItems([
                    Action::make('team-settings')
                        ->label('Team Settings')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->url(fn (): UrlGenerator|string => $this->shouldRegisterMenuItem()
                            ? url(Pages\EditTeam::getUrl())
                            : url($panel->getPath())),
                ]);
        }

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

        /**
         * Use a tenant-aware LoginResponse so that users without a team are
         * redirected to /app/new (team creation) rather than landing on the
         * bare /app panel root.  This binding is placed in boot() to run
         * after Filament's own register() bindings so that ours takes
         * precedence.
         */
        $this->app->singleton(
            LoginResponse::class,
            \App\Http\Responses\Auth\LoginResponse::class,
        );

        $this->app->singleton(
            RegisterResponse::class,
            \App\Http\Responses\Auth\RegisterResponse::class,
        );

        /**
         * Listen and create personal team for new accounts.
         */
        Event::listen(
            Registered::class,
            CreatePersonalTeam::class,
        );

        /**
         * Listen and switch team if tenant was changed.
         */
        Event::listen(
            TenantSet::class,
            SwitchTeam::class,
        );
    }

    public function shouldRegisterMenuItem(): bool
    {
        $hasVerifiedEmail = ! is_null(auth()->user()); // ?->hasVerifiedEmail();

        // Check if Filament is properly initialized before using facades
        if (! app()->bound('filament')) {
            return $hasVerifiedEmail;
        }

        try {
            return Filament::hasTenancy()
                ? $hasVerifiedEmail && Filament::getTenant()
                : $hasVerifiedEmail;
        } catch (\Exception) {
            // Fallback if facade is not ready
            return $hasVerifiedEmail;
        }
    }
}
