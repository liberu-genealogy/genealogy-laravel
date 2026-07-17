<?php

namespace App\Modules\Admin\Providers;

use App\Modules\Admin\Services\AdminService;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        // Register admin services
        $this->app->singleton(AdminService::class, fn ($app) => new AdminService);

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
        // Guarded because none of these module resource directories exist. An
        // unguarded loadViewsFrom() is harmless at runtime but fatal to
        // `php artisan view:cache`, which walks every registered path — so this
        // passed in dev and broke the production image on boot. ModuleServiceProvider
        // already guards the same call this way.
        if (is_dir(__DIR__.'/../resources/views')) {
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin');
        }

        // Load admin translations
        if (is_dir(__DIR__.'/../resources/lang')) {
            $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'admin');
        }

        // Publish admin assets
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('modules/admin'),
        ], 'admin-assets');

        // Publish admin configuration
        $this->publishes([
            __DIR__.'/../config/admin.php' => config_path('admin.php'),
        ], 'admin-config');
    }
}
