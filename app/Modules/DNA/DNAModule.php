<?php

namespace App\Modules\DNA;

use App\Modules\BaseModule;

class DNAModule extends BaseModule
{
    protected function onEnable(): void
    {
        // Register DNA-specific services
        $this->registerDNAServices();
    }

    protected function onDisable(): void
    {
        // Clean up DNA-specific services
        $this->unregisterDNAServices();
    }

    protected function onInstall(): void
    {
        // Install DNA-related database tables
        $this->installDNATables();
    }

    protected function onUninstall(): void
    {
        // Remove DNA-related data
        $this->removeDNATables();
    }

    /**
     * Register DNA-specific services.
     */
    protected function registerDNAServices(): void
    {
        app()->singleton('genealogy.dna', function ($app) {
            return new \App\Modules\DNA\Services\DNAService();
        });

        app()->singleton('genealogy.dna.matcher', function ($app) {
            return new \App\Modules\DNA\Services\DNAMatchService();
        });
    }

    /**
     * Unregister DNA-specific services.
     */
    protected function unregisterDNAServices(): void
    {
        // Clean up registered services
    }

    /**
     * Install DNA-related database tables.
     */
    protected function installDNATables(): void
    {
        \Artisan::call('migrate', [
            '--path' => 'app/Modules/DNA/database/migrations',
            '--force' => true,
        ]);
    }

    /**
     * Remove DNA-related tables.
     */
    protected function removeDNATables(): void
    {
        // Careful implementation needed
    }
}