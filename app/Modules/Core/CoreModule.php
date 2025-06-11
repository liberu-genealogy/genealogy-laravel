<?php

namespace App\Modules\Core;

use App\Modules\BaseModule;

class CoreModule extends BaseModule
{
    protected function onEnable(): void
    {
        // Core module is always enabled - contains shared functionality
    }

    protected function onDisable(): void
    {
        // Core module cannot be disabled
        throw new \Exception('Core module cannot be disabled as it contains essential functionality.');
    }

    protected function onInstall(): void
    {
        // Install core database tables and configurations
        $this->publishCoreAssets();
    }

    protected function onUninstall(): void
    {
        // Core module cannot be uninstalled
        throw new \Exception('Core module cannot be uninstalled as it contains essential functionality.');
    }

    /**
     * Publish core module assets and configurations.
     */
    protected function publishCoreAssets(): void
    {
        // Publish core configurations
        \Artisan::call('vendor:publish', [
            '--tag' => 'core-config',
            '--force' => true,
        ]);
    }
}