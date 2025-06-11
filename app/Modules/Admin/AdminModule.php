<?php

namespace App\Modules\Admin;

use App\Modules\BaseModule;

class AdminModule extends BaseModule
{
    protected function onEnable(): void
    {
        // Register admin-specific services
        $this->registerAdminServices();
    }

    protected function onDisable(): void
    {
        // Clean up admin-specific services
        $this->unregisterAdminServices();
    }

    protected function onInstall(): void
    {
        // Install admin-related database tables
        $this->installAdminTables();
    }

    protected function onUninstall(): void
    {
        // Remove admin-related data
        $this->removeAdminTables();
    }

    /**
     * Register admin-specific services.
     */
    protected function registerAdminServices(): void
    {
        app()->singleton('genealogy.admin', function ($app) {
            return new \App\Modules\Admin\Services\AdminService();
        });

        app()->singleton('genealogy.admin.types', function ($app) {
            return new \App\Modules\Admin\Services\TypeService();
        });

        app()->singleton('genealogy.admin.changes', function ($app) {
            return new \App\Modules\Admin\Services\ChangeService();
        });
    }

    /**
     * Unregister admin-specific services.
     */
    protected function unregisterAdminServices(): void
    {
        // Clean up registered services
    }

    /**
     * Install admin-related database tables.
     */
    protected function installAdminTables(): void
    {
        \Artisan::call('migrate', [
            '--path' => 'app/Modules/Admin/database/migrations',
            '--force' => true,
        ]);
    }

    /**
     * Remove admin-related tables.
     */
    protected function removeAdminTables(): void
    {
        // Careful implementation needed
    }
}