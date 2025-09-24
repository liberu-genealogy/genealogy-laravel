<?php

namespace App\Modules\Sources;

use App\Modules\Sources\Services\SourcesService;
use App\Modules\Sources\Services\CitationService;
use App\Modules\Sources\Services\RepositoryService;
use Artisan;
use App\Modules\BaseModule;

class SourcesModule extends BaseModule
{
    protected function onEnable(): void
    {
        // Register sources-specific services
        $this->registerSourcesServices();
    }

    protected function onDisable(): void
    {
        // Clean up sources-specific services
        $this->unregisterSourcesServices();
    }

    protected function onInstall(): void
    {
        // Install sources-related database tables
        $this->installSourcesTables();
        $this->seedSourcesData();
    }

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
        app()->singleton('genealogy.sources', function ($app) {
            return new SourcesService();
        });

        app()->singleton('genealogy.citations', function ($app) {
            return new CitationService();
        });

        app()->singleton('genealogy.repositories', function ($app) {
            return new RepositoryService();
        });
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