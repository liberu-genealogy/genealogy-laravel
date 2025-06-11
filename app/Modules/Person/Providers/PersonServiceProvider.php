<?php

namespace App\Modules\Person\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Person\Services\PersonService;

class PersonServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register person service
        $this->app->singleton(PersonService::class, function ($app) {
            return new PersonService();
        });

        // Register person configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/person.php', 'person');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load person routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        // Load person views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'person');

        // Load person translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'person');

        // Publish person assets
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('modules/person'),
        ], 'person-assets');

        // Publish person configuration
        $this->publishes([
            __DIR__ . '/../config/person.php' => config_path('person.php'),
        ], 'person-config');
    }
}