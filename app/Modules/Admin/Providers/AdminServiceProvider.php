<?php

namespace App\Modules\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Admin\Services\AdminService;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register admin services
        $this->app->singleton(AdminService::class, function ($app) {
            return new AdminService();
        });

        // Register admin configuration
        // $this->mergeConfigFrom(__DIR__ . '/../config/admin.php', 'admin');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load admin routes
        // $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        // $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        // Load admin views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');

        // Load admin translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'admin');

        // Publish admin assets
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('modules/admin'),
        ], 'admin-assets');

        // Publish admin configuration
        $this->publishes([
            __DIR__ . '/../config/admin.php' => config_path('admin.php'),
        ], 'admin-config');
    }
}