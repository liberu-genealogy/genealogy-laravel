<?php

namespace App\Providers;

use App\Actions\Socialstream\CreateConnectedAccount;
use App\Actions\Socialstream\CreateUserWithTeamsFromProvider;
use App\Actions\Socialstream\GenerateRedirectForProvider;
use App\Actions\Socialstream\HandleInvalidState;
use App\Actions\Socialstream\ResolveSocialiteUser;
use App\Actions\Socialstream\UpdateConnectedAccount;
use Illuminate\Support\ServiceProvider;
use JoelButcher\Socialstream\Concerns\ConfirmsFilament;
use JoelButcher\Socialstream\Socialstream;

class SocialstreamServiceProvider extends ServiceProvider
{
    use ConfirmsFilament;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Socialstream::resolvesSocialiteUsersUsing(ResolveSocialiteUser::class);
        // Teams variant, not the stub's CreateUserFromProvider: Jetstream teams
        // are on and the app panel is Team-tenant-scoped, so a user created
        // without a personal team lands in a tenant-scoped panel with no tenant.
        Socialstream::createUsersFromProviderUsing(CreateUserWithTeamsFromProvider::class);
        Socialstream::createConnectedAccountsUsing(CreateConnectedAccount::class);
        Socialstream::updateConnectedAccountsUsing(UpdateConnectedAccount::class);
        Socialstream::handlesInvalidStateUsing(HandleInvalidState::class);
        Socialstream::generatesProvidersRedirectsUsing(GenerateRedirectForProvider::class);
    }
}
