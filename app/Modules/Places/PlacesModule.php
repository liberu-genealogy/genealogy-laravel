<?php

namespace App\Modules\Places;

use App\Modules\BaseModule;
use Artisan;

class PlacesModule extends BaseModule
{
    #[\Override]
    protected function onEnable(): void
    {
        // Register places-specific services
        $this->registerPlacesServices();
    }

    #[\Override]
    protected function onDisable(): void
    {
        // Clean up places-specific services
        $this->unregisterPlacesServices();
    }

    #[\Override]
    protected function onInstall(): void
    {
        // Install places-related database tables
        $this->installPlacesTables();
        $this->seedPlacesData();
    }

    #[\Override]
    protected function onUninstall(): void
    {
        // Module data is deliberately retained. See BaseModule::uninstall().
    }

    /**
     * Register places-specific services.
     */
    protected function registerPlacesServices(): void
    {
        // Geocoding was removed: it read latitude/longitude/name columns the places
        // table does not have, so it could not run. Reinstating it is a schema change
        // and a feature, not a service registration.
    }

    /**
     * Unregister places-specific services.
     */
    protected function unregisterPlacesServices(): void
    {
        // Clean up registered services
    }

    /**
     * Install places-related database tables.
     */
    protected function installPlacesTables(): void
    {
        Artisan::call('migrate', [
            '--path' => 'app/Modules/Places/database/migrations',
            '--force' => true,
        ]);
    }

    /**
     * Seed places data.
     */
    protected function seedPlacesData(): void
    {
        Artisan::call('db:seed', [
            '--class' => 'App\\Modules\\Places\\Database\\Seeders\\PlacesSeeder',
            '--force' => true,
        ]);
    }
}
