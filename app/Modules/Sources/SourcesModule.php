<?php

namespace App\Modules\Sources;

use App\Modules\Sources\Services\SourcesService;
use App\Modules\Sources\Services\CitationService;
use App\Modules\Sources\Services\RepositoryService;
use Artisan;
use App\Modules\BaseModule;

class SourcesModule extends BaseModule
{
    #[\Override]
    protected function onEnable(): void
    {
        // Register sources-specific services
        $this->registerSourcesServices();
    }

    #[\Override]
    protected function onDisable(): void
    {
        // Clean up sources-specific services
        $this->unregisterSourcesServices();
    }

    #[\Override]
    protected function onInstall(): void
    {
        // Install sources-related database tables
        $this->installSourcesTables();
        $this->seedSourcesData();
    }

    #[\Override]
    protected function onUninstall(): void
    {
        // Remove sources-related data
        $this->removeSourcesTables();
    }

    /**
     * Register sources-specific services.
     */
    protected function registerSourcesServices(): void
    {
        app()->singleton('genealogy.sources', fn($app) => new SourcesService());

        app()->singleton('genealogy.citations', fn($app) => new CitationService());

        app()->singleton('genealogy.repositories', fn($app) => new RepositoryService());
    }

    /**
     * Unregister sources-specific services.
     */
    protected function unregisterSourcesServices(): void
    {
        // Clean up registered services
    }

    /**
     * Install sources-related database tables.
     */
    protected function installSourcesTables(): void
    {
        Artisan::call('migrate', [
            '--path' => 'app/Modules/Sources/database/migrations',
            '--force' => true,
        ]);
    }

    /**
     * Seed sources data.
     */
    protected function seedSourcesData(): void
    {
        Artisan::call('db:seed', [
            '--class' => 'App\\Modules\\Sources\\Database\\Seeders\\SourcesSeeder',
            '--force' => true,
        ]);
    }

    /**
     * Remove sources-related tables.
     */
    protected function removeSourcesTables(): void
    {
        // Careful implementation needed
    }
}