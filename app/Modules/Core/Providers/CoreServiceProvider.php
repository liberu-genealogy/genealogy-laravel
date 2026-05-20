<?php

namespace App\Modules\Core\Providers;

use App\Modules\Core\Services\TreeService;
use App\Modules\Core\Services\GedcomService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register core services
        $this->registerCoreServices();
        $this->registerCoreConfig();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootCoreServices();
        $this->publishCoreAssets();
    }

    /**
     * Register core services.
     */
    protected function registerCoreServices(): void
    {
        // Register shared genealogy services
        $this->app->singleton('genealogy.tree', function ($app) {
            return new TreeService();
        });

        $this->app->singleton('genealogy.gedcom', function ($app) {
            return new GedcomService();
        });
    }

    /**
     * Register core configuration.
     */
    protected function registerCoreConfig(): void
    {
        $configPath = __DIR__ . '/../config/genealogy.php';
        if (File::exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'genealogy');
        }
    }

    /**
     * Boot core services.
     */
    protected function bootCoreServices(): void
    {
        // Load core views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'core');
        
        // Load core translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'core');
        
        // Load core routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    /**
     * Publish core assets.
     */
    protected function publishCoreAssets(): void
    {
        // Publish core configuration
        $this->publishes([
            __DIR__ . '/../config/genealogy.php' => config_path('genealogy.php'),
        ], 'core-config');

        // Publish core assets
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('modules/core'),
        ], 'core-assets');
    }
}