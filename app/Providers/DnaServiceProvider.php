<?php

namespace App\Providers;

use App\Console\Commands\MatchKitsCommand;
use App\Console\Commands\ProcessLargeScaleDnaCommand;
use App\Console\Commands\BulkImportDnaCommand;
use App\Console\Commands\TriangulateDnaCommand;
use App\Services\AdvancedDnaMatchingService;
use App\Services\DnaImportService;
use App\Services\DnaTriangulationService;
use Illuminate\Support\ServiceProvider;
//use LiberuGenealogy\LaravelDna\Services\DnaAnalysisService;

class DnaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the AdvancedDnaMatchingService
        $this->app->singleton(AdvancedDnaMatchingService::class, function ($app) {
            return new AdvancedDnaMatchingService();
        });

        // Register the DnaImportService
        $this->app->singleton(DnaImportService::class, function ($app) {
            return new DnaImportService();
        });

        // Register the DnaTriangulationService
        $this->app->singleton(DnaTriangulationService::class, function ($app) {
            return new DnaTriangulationService(
                $app->make(AdvancedDnaMatchingService::class)
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
                MatchKitsCommand::class,
                ProcessLargeScaleDnaCommand::class,
                BulkImportDnaCommand::class,
                TriangulateDnaCommand::class,
            ]);
        }
    }
}
