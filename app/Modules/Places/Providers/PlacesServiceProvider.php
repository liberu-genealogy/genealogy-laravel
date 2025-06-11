<?php

namespace App\Modules\Places\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Places\Services\PlacesService;
use App\Modules\Places\Services\GeocodingService;

class PlacesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register places services
        $this->app->singleton(PlacesService::class, function ($app) {
            return new PlacesService();
        });

        $this->app->singleton(GeocodingService::class, function ($app) {
            return new GeocodingService();
        });

        // Register places configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/places.php', 'places');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load places routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        // Load places views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'places');

        // Load places translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'places');

        // Publish places assets
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('modules/places'),
        ], 'places-assets');

        // Publish places configuration
        $this->publishes([
            __DIR__ . '/../config/places.php' => config_path('places.php'),
        ], 'places-config');
    }
}