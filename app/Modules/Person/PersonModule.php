<?php

namespace App\Modules\Person;

use App\Modules\BaseModule;
use App\Modules\Person\Services\PersonService;
use Artisan;

class PersonModule extends BaseModule
{
    #[\Override]
    protected function onEnable(): void
    {
        // Register person-specific services and configurations
        $this->registerPersonServices();
    }

    #[\Override]
    protected function onDisable(): void
    {
        // Clean up person-specific services
        $this->unregisterPersonServices();
    }

    #[\Override]
    protected function onInstall(): void
    {
        // Install person-related database tables
        $this->installPersonTables();
        $this->seedPersonData();
    }

    #[\Override]
    protected function onUninstall(): void
    {
        // Module data is deliberately retained. See BaseModule::uninstall().
    }

    /**
     * Register person-specific services.
     */
    protected function registerPersonServices(): void
    {
        app()->singleton('genealogy.person', fn ($app) => new PersonService);
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
        Artisan::call('migrate', [
            '--path' => 'app/Modules/Person/database/migrations',
            '--force' => true,
        ]);
    }

    /**
     * Seed person data.
     */
    protected function seedPersonData(): void
    {
        Artisan::call('db:seed', [
            '--class' => 'App\\Modules\\Person\\Database\\Seeders\\PersonSeeder',
            '--force' => true,
        ]);
    }
}
