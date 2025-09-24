<?php

namespace App\Modules\Tree;

use App\Modules\Tree\Services\TreeBuilderService;
use App\Modules\Tree\Services\TreeRenderService;
use Artisan;
use File;
use App\Modules\BaseModule;

class TreeModule extends BaseModule
{
    protected function onEnable(): void
    {
        // Register tree-specific services
        $this->registerTreeServices();
    }

    protected function onDisable(): void
    {
        // Clean up tree-specific services
        $this->unregisterTreeServices();
    }

    protected function onInstall(): void
    {
        // Install tree-related assets and configurations
        $this->installTreeAssets();
    }

    protected function onUninstall(): void
    {
        // Remove tree-related assets
        $this->removeTreeAssets();
    }

    /**
     * Register tree-specific services.
     */
    protected function registerTreeServices(): void
    {
        app()->singleton('genealogy.tree.builder', function ($app) {
            return new TreeBuilderService();
        });

        app()->singleton('genealogy.tree.renderer', function ($app) {
            return new TreeRenderService();
        });
    }

    /**
     * Unregister tree-specific services.
     */
    protected function unregisterTreeServices(): void
    {
        // Clean up registered services
    }

    /**
     * Install tree-related assets.
     */
    protected function installTreeAssets(): void
    {
        Artisan::call('vendor:publish', [
            '--tag' => 'tree-assets',
            '--force' => true,
        ]);
    }

    /**
     * Remove tree-related assets.
     */
    protected function removeTreeAssets(): void
    {
        $assetsPath = public_path('modules/tree');
        if (File::exists($assetsPath)) {
            File::deleteDirectory($assetsPath);
        }
    }
}