<?php

namespace App\Modules\Import;

use App\Modules\BaseModule;
use App\Modules\Import\Services\ExportService;
use App\Modules\Import\Services\GedcomImportService;
use App\Modules\Import\Services\ImportService;
use Artisan;

class ImportModule extends BaseModule
{
    #[\Override]
    protected function onEnable(): void
    {
        // Register import-specific services
        $this->registerImportServices();
    }

    #[\Override]
    protected function onDisable(): void
    {
        // Clean up import-specific services
        $this->unregisterImportServices();
    }

    #[\Override]
    protected function onInstall(): void
    {
        // Install import-related database tables
        $this->installImportTables();
    }

    #[\Override]
    protected function onUninstall(): void
    {
        // Module data is deliberately retained. See BaseModule::uninstall().
    }

    /**
     * Register import-specific services.
     */
    protected function registerImportServices(): void
    {
        app()->singleton('genealogy.import', fn ($app) => new ImportService);

        app()->singleton('genealogy.import.gedcom', fn ($app) => new GedcomImportService);

        app()->singleton('genealogy.export', fn ($app) => new ExportService);
    }

    /**
     * Unregister import-specific services.
     */
    protected function unregisterImportServices(): void
    {
        // Clean up registered services
    }

    /**
     * Install import-related database tables.
     */
    protected function installImportTables(): void
    {
        Artisan::call('migrate', [
            '--path' => 'app/Modules/Import/database/migrations',
            '--force' => true,
        ]);
    }
}
