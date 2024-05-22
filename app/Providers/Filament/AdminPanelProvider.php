<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Tenancy\EditTeamProfile;
use App\Filament\Pages\Tenancy\RegisterTeam;
use App\Models\Team;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Resources;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use Illuminate\Support\Facades\Event;
use JeffGreco13\FilamentBreezy\FilamentBreezy;
use Livewire\Livewire;

//use App\Providers\Filament\SyncSpatiePermissionsWithFilamentTenants;

use Filament\PluginServiceProvider;

class AdminPanelProvider extends PluginServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('admin')
            ->path('admin');
    }

    protected function getPages(): array
    {
        return [
            Pages\Dashboard::class,
        ];
    }

    protected function getResources(): array
    {
        return [];
    }

    protected function getWidgets(): array
    {
        return [
            Widgets\AccountWidget::class,
            Widgets\FilamentInfoWidget::class,
        ];
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();

        Filament::registerPages($this->getPages());
        Filament::registerResources($this->getResources());
        Filament::registerWidgets($this->getWidgets());

        Filament::registerStyles([
            'https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css',
        ]);

        Filament::registerScripts([
            asset('js/app.js'),
        ]);

        Filament::serving(function () {
            Filament::registerTheme(mix('css/app.css'));
        });

        Filament::registerUserMenuItems([
            'account' => UserMenuItem::make()->url(route('filament.pages.profile')),
            'logout' => UserMenuItem::make()->url(route('filament.auth.logout')),
        ]);

        Filament::registerNavigationGroups([
            NavigationGroup::make()
                ->label('Shop')
                ->icon('heroicon-s-shopping-cart'),
        ]);

        Filament::registerNavigationItems([
            NavigationItem::make('Dashboard')
                ->icon('heroicon-o-home')
                ->activeIcon('heroicon-s-home')
                ->isActiveWhen(fn (): bool => request()->routeIs('filament.pages.dashboard'))
                ->url(route('filament.pages.dashboard')),
        ]);

        Filament::serving(function () {
            Filament::registerMiddleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DispatchServingFilamentEvent::class,
            ]);
        });

        Filament::auth(function (Authenticate $auth) {
            $auth
                ->guard('web')
                ->redirect('/')
                ->authenticate();
        });

        Filament::registerRenderHook(
            'head.start',
            fn (): string => '<!-- Render hook content -->'
        );
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        Livewire::component('filament.core.auth.login', Http\Livewire\Auth\Login::class);
        Livewire::component('filament.core.pages.dashboard', Http\Livewire\Pages\Dashboard::class);

        FilamentBreezy::setTenantModel(Team::class);
        FilamentBreezy::setTenantRegistrationPage(RegisterTeam::class);
        FilamentBreezy::setTenantProfilePage(EditTeamProfile::class);
    }
}