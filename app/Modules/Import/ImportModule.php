<?php

namespace App\Modules\Import;

use App\Modules\Import\Services\ImportService;
use App\Modules\Import\Services\GedcomImportService;
use App\Modules\Import\Services\ExportService;
use Artisan;
use App\Modules\BaseModule;

class ImportModule extends BaseModule
{
    protected function onEnable(): void
    {
        // Register import-specific services
        $this->registerImportServices();
    }

    protected function onDisable(): void
    {
        // Clean up import-specific services
        $this->unregisterImportServices();
    }

    protected function onInstall(): void
    {
        // Install import-related database tables
        $this->installImportTables();
    }

    protected function onUninstall(): void
    {
        // Remove import-related data
        $this->removeImportTables();
    }

    /**
     * Register import-specific services.
     */
    protected function registerImportServices(): void
    {
        app()->singleton('genealogy.import', function ($app) {
            return new ImportService();
        });

        app()->singleton('genealogy.import.gedcom', function ($app) {
            return new GedcomImportService();
        });

        app()->singleton('genealogy.export', function ($app) {
            return new ExportService();
        });
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

    /**
     * Remove import-related tables.
     */
    protected function removeImportTables(): void
    {
        // Careful implementation needed
    }
}