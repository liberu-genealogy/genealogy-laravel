<?php

namespace App\Modules\Tree;

use App\Modules\Tree\Services\TreeBuilderService;
use App\Modules\Tree\Services\TreeRenderService;
use Artisan;
use File;
use App\Modules\BaseModule;

class TreeModule extends BaseModule
{
    #[\Override]
    protected function onEnable(): void
    {
        // Register tree-specific services
        $this->registerTreeServices();
    }

    #[\Override]
    protected function onDisable(): void
    {
        // Clean up tree-specific services
        $this->unregisterTreeServices();
    }

    #[\Override]
    protected function onInstall(): void
    {
        // Install tree-related assets and configurations
        $this->installTreeAssets();
    }

    #[\Override]
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
        app()->singleton('genealogy.tree.builder', fn($app) => new TreeBuilderService());

        app()->singleton('genealogy.tree.renderer', fn($app) => new TreeRenderService());
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