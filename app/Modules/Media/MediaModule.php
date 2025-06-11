<?php

namespace App\Modules\Media;

use App\Modules\BaseModule;

class MediaModule extends BaseModule
{
    protected function onEnable(): void
    {
        // Register media-specific services
        $this->registerMediaServices();
    }

    protected function onDisable(): void
    {
        // Clean up media-specific services
        $this->unregisterMediaServices();
    }

    protected function onInstall(): void
    {
        // Install media-related database tables
        $this->installMediaTables();
        $this->publishMediaAssets();
    }

    protected function onUninstall(): void
    {
        // Remove media-related data
        $this->removeMediaTables();
    }

    /**
     * Register media-specific services.
     */
    protected function registerMediaServices(): void
    {
        app()->singleton('genealogy.media', function ($app) {
            return new \App\Modules\Media\Services\MediaService();
        });

        app()->singleton('genealogy.media.processor', function ($app) {
            return new \App\Modules\Media\Services\MediaProcessorService();
        });
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
        \Artisan::call('migrate', [
            '--path' => 'app/Modules/Media/database/migrations',
            '--force' => true,
        ]);
    }

    /**
     * Publish media assets.
     */
    protected function publishMediaAssets(): void
    {
        \Artisan::call('vendor:publish', [
            '--tag' => 'media-assets',
            '--force' => true,
        ]);
    }

    /**
     * Remove media-related tables.
     */
    protected function removeMediaTables(): void
    {
        // Careful implementation needed
    }
}