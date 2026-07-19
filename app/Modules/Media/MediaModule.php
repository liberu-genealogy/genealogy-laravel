<?php

namespace App\Modules\Media;

use App\Modules\BaseModule;
use App\Modules\Media\Services\MediaProcessorService;
use App\Modules\Media\Services\MediaService;
use Artisan;

class MediaModule extends BaseModule
{
    #[\Override]
    protected function onEnable(): void
    {
        // Register media-specific services
        $this->registerMediaServices();
    }

    #[\Override]
    protected function onDisable(): void
    {
        // Clean up media-specific services
        $this->unregisterMediaServices();
    }

    #[\Override]
    protected function onInstall(): void
    {
        // Install media-related database tables
        $this->installMediaTables();
        $this->publishMediaAssets();
    }

    #[\Override]
    protected function onUninstall(): void
    {
        // Module data is deliberately retained. See BaseModule::uninstall().
    }

    /**
     * Register media-specific services.
     */
    protected function registerMediaServices(): void
    {
        app()->singleton('genealogy.media', fn ($app) => new MediaService);

        app()->singleton('genealogy.media.processor', fn ($app) => new MediaProcessorService);
    }

    /**
     * Unregister media-specific services.
     */
    protected function unregisterMediaServices(): void
    {
        // Clean up registered services
    }

    /**
     * Install media-related database tables.
     */
    protected function installMediaTables(): void
    {
        Artisan::call('migrate', [
            '--path' => 'app/Modules/Media/database/migrations',
            '--force' => true,
        ]);
    }

    /**
     * Publish media assets.
     */
    protected function publishMediaAssets(): void
    {
        Artisan::call('vendor:publish', [
            '--tag' => 'media-assets',
            '--force' => true,
        ]);
    }
}
