<?php

namespace App\Providers;

use App\Models\Person;
use App\Models\Family;
use App\Models\PersonEvent;
use App\Observers\PersonObserver;
use App\Observers\FamilyObserver;
use App\Observers\PersonEventObserver;
use App\Services\GamificationService;
use Illuminate\Support\ServiceProvider;

class GamificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(GamificationService::class, function ($app) {
            return new GamificationService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register model observers
        Person::observe(PersonObserver::class);
        Family::observe(FamilyObserver::class);
        PersonEvent::observe(PersonEventObserver::class);
    }
}