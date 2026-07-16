<?php

namespace App\Modules\Core;

use App\Modules\BaseModule;
use Artisan;
use Exception;

class CoreModule extends BaseModule
{
    #[\Override]
    protected function onEnable(): void
    {
        // Core module is always enabled - contains shared functionality
    }

    #[\Override]
    protected function onDisable(): void
    {
        // Core module cannot be disabled
        throw new Exception('Core module cannot be disabled as it contains essential functionality.');
    }

    #[\Override]
    protected function onInstall(): void
    {
        // Install core database tables and configurations
        $this->publishCoreAssets();
    }

    #[\Override]
    protected function onUninstall(): void
    {
        // Core module cannot be uninstalled
        throw new Exception('Core module cannot be uninstalled as it contains essential functionality.');
    }

    /**
     * Publish core module assets and configurations.
     */
    protected function publishCoreAssets(): void
    {
        // Publish core configurations
        Artisan::call('vendor:publish', [
            '--tag' => 'core-config',
            '--force' => true,
        ]);
    }
}
