<?php

namespace App\Modules\Places;

use App\Modules\Places\Services\PlacesService;
use App\Modules\Places\Services\GeocodingService;
use Artisan;
use App\Modules\BaseModule;

class PlacesModule extends BaseModule
{
    protected function onEnable(): void
    {
        // Register places-specific services
        $this->registerPlacesServices();
    }

    protected function onDisable(): void
    {
        // Clean up places-specific services
        $this->unregisterPlacesServices();
    }

    protected function onInstall(): void
    {
        // Install places-related database tables
        $this->installPlacesTables();
        $this->seedPlacesData();
    }

    protected function onUninstall(): void
    {
        // Remove places-related data
        $this->removePlacesTables();
    }

    /**
     * Register places-specific services.
     */
    protected function registerPlacesServices(): void
    {
        app()->singleton('genealogy.places', function ($app) {
            return new PlacesService();
        });

        app()->singleton('genealogy.places.geocoder', function ($app) {
            return new GeocodingService();
        });
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

    /**
     * Remove places-related tables.
     */
    protected function removePlacesTables(): void
    {
        // Careful implementation needed
    }
}