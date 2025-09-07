<?php

namespace App\Providers;

use App\Services\AdvancedDnaMatchingService;
use Illuminate\Support\ServiceProvider;
use LiberuGenealogy\LaravelDna\Services\DnaAnalysisService;

class DnaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the DnaAnalysisService if not already registered by the package
        $this->app->singleton(DnaAnalysisService::class, function ($app) {
            return new DnaAnalysisService();
        });

        // Register the AdvancedDnaMatchingService
        $this->app->singleton(AdvancedDnaMatchingService::class, function ($app) {
            return new AdvancedDnaMatchingService(
                $app->make(DnaAnalysisService::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\MatchKitsCommand::class,
                \App\Console\Commands\ProcessLargeScaleDnaCommand::class,
            ]);
        }
    }
}