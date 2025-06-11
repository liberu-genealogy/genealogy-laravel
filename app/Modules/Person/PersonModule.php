<?php

namespace App\Modules\Person;

use App\Modules\BaseModule;

class PersonModule extends BaseModule
{
    protected function onEnable(): void
    {
        // Register person-specific services and configurations
        $this->registerPersonServices();
    }

    protected function onDisable(): void
    {
        // Clean up person-specific services
        $this->unregisterPersonServices();
    }

    protected function onInstall(): void
    {
        // Install person-related database tables
        $this->installPersonTables();
        $this->seedPersonData();
    }

    protected function onUninstall(): void
    {
        // Remove person-related data (with confirmation)
        $this->removePersonTables();
    }

    /**
     * Register person-specific services.
     */
    protected function registerPersonServices(): void
    {
        app()->singleton('genealogy.person', function ($app) {
            return new \App\Modules\Person\Services\PersonService();
        });
    }

    /**
     * Unregister person-specific services.
     */
    protected function unregisterPersonServices(): void
    {
        // Clean up registered services
    }

    /**
     * Install person-related database tables.
     */
    protected function installPersonTables(): void
    {
        \Artisan::call('migrate', [
            '--path' => 'app/Modules/Person/database/migrations',
            '--force' => true,
        ]);
    }

    /**
     * Seed person data.
     */
    protected function seedPersonData(): void
    {
        \Artisan::call('db:seed', [
            '--class' => 'App\\Modules\\Person\\Database\\Seeders\\PersonSeeder',
            '--force' => true,
        ]);
    }

    /**
     * Remove person-related tables.
     */
    protected function removePersonTables(): void
    {
        // This would require careful implementation to avoid data loss
        // Should include confirmation and backup mechanisms
    }
}