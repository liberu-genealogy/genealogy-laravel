<?php

namespace App\Providers;

use Exception;
use Log;
use App\Modules\ModuleManager;
use App\Modules\ModuleServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the module manager as a singleton
        $this->app->singleton(ModuleManager::class, function ($app) {
            return new ModuleManager();
        });

        // Register the module service provider
        $this->app->register(ModuleServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.debug')) {
            // \DB::listen(function ($query): void {
            //     \Log::info(
            //         $query->sql,
            //         $query->bindings,
            //         $query->time
            //     );
            // });
        }

        // Register Livewire components here
        // Livewire::component('devilliers-report', DevilliersReport::class);
        // Livewire::component('descendant-chart-component', DescendantChartComponent::class);
        // Livewire::component('people-search', PeopleSearch::class);
        // Livewire::component('pedigree-chart', PedigreeChart::class);
        // Livewire::component('create-team', CreateTeam::class);
        // Livewire::component('edit-profile', EditProfile::class);
        // Enable default modules on first boot
        $this->enableDefaultModules();
    }

    /**
     * Enable default modules if they haven't been enabled yet.
     */
    protected function enableDefaultModules(): void
    {
        $moduleManager = app(ModuleManager::class);
        $defaultModules = config('modules.default_enabled', []);

        foreach ($defaultModules as $moduleName) {
            if ($moduleManager->has($moduleName) && !$moduleManager->get($moduleName)->isEnabled()) {
                try {
                    $moduleManager->enable($moduleName);
                } catch (Exception $e) {
                    // Log error but don't break the application
                    Log::warning("Failed to enable default module {$moduleName}: " . $e->getMessage());
                }
            }
        }
    }
}
