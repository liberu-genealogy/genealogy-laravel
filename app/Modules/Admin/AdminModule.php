<?php

namespace App\Modules\Admin;

use App\Modules\Admin\Services\AdminService;
use App\Modules\Admin\Services\TypeService;
use App\Modules\Admin\Services\ChangeService;
use Artisan;
use App\Modules\BaseModule;

class AdminModule extends BaseModule
{
    #[\Override]
    protected function onEnable(): void
    {
        // Register admin-specific services
        $this->registerAdminServices();
    }

    #[\Override]
    protected function onDisable(): void
    {
        // Clean up admin-specific services
        $this->unregisterAdminServices();
    }

    #[\Override]
    protected function onInstall(): void
    {
        // Install admin-related database tables
        $this->installAdminTables();
    }

    #[\Override]
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
        app()->singleton('genealogy.admin', fn($app) => new AdminService());

        app()->singleton('genealogy.admin.types', fn($app) => new TypeService());

        app()->singleton('genealogy.admin.changes', fn($app) => new ChangeService());
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
        Artisan::call('migrate', [
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