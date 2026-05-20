<?php

namespace App\Modules\Family;

use App\Modules\Family\Services\FamilyService;
use Artisan;
use App\Modules\BaseModule;

class FamilyModule extends BaseModule
{
    protected function onEnable(): void
    {
        // Register family-specific services
        $this->registerFamilyServices();
    }

    protected function onDisable(): void
    {
        // Clean up family-specific services
        $this->unregisterFamilyServices();
    }

    protected function onInstall(): void
    {
        // Install family-related database tables
        $this->installFamilyTables();
        $this->seedFamilyData();
    }

    protected function onUninstall(): void
    {
        // Remove family-related data
        $this->removeFamilyTables();
    }

    /**
     * Register family-specific services.
     */
    protected function registerFamilyServices(): void
    {
        app()->singleton('genealogy.family', function ($app) {
            return new FamilyService();
        });
    }

    /**
     * Unregister family-specific services.
     */
    protected function unregisterFamilyServices(): void
    {
        // Clean up registered services
    }

    /**
     * Install family-related database tables.
     */
    protected function installFamilyTables(): void
    {
        Artisan::call('migrate', [
            '--path' => 'app/Modules/Family/database/migrations',
            '--force' => true,
        ]);
    }

    /**
     * Seed family data.
     */
    protected function seedFamilyData(): void
    {
        Artisan::call('db:seed', [
            '--class' => 'App\\Modules\\Family\\Database\\Seeders\\FamilySeeder',
            '--force' => true,
        ]);
    }

    /**
     * Remove family-related tables.
     */
    protected function removeFamilyTables(): void
    {
        // Careful implementation needed to avoid data loss
    }
}