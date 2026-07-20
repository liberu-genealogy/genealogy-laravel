<?php

namespace App\Modules\Places\Providers;

use Illuminate\Support\ServiceProvider;

class PlacesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        // Register places configuration
        // $this->mergeConfigFrom(__DIR__ . '/../config/places.php', 'places');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load places routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        // $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        // Load places views
        // Guarded because none of these module resource directories exist. An
        // unguarded loadViewsFrom() is harmless at runtime but fatal to
        // `php artisan view:cache`, which walks every registered path — so this
        // passed in dev and broke the production image on boot. ModuleServiceProvider
        // already guards the same call this way.
        if (is_dir(__DIR__.'/../resources/views')) {
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'places');
        }

        // Load places translations
        if (is_dir(__DIR__.'/../resources/lang')) {
            $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'places');
        }

        // Publish places assets
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('modules/places'),
        ], 'places-assets');

        // Publish places configuration
        $this->publishes([
            __DIR__.'/../config/places.php' => config_path('places.php'),
        ], 'places-config');
    }
}
