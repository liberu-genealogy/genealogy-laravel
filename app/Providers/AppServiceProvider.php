<?php

namespace App\Providers;

use App\Models\Family;
use App\Models\Person;
use App\Models\Source;
use App\Modules\ModuleManager;
use App\Modules\ModuleServiceProvider;
use Exception;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Log;
use Spatie\Permission\PermissionRegistrar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        // Bind vendor Person/Family models to the app's versions so that
        // the GEDCOM parser (which calls app(VendorPerson::class)) resolves
        // to our models and therefore uses the correct "people" / "families"
        // tables.
        $this->app->bind(\FamilyTree365\LaravelGedcom\Models\Person::class, Person::class);
        $this->app->bind(\FamilyTree365\LaravelGedcom\Models\Family::class, Family::class);
        $this->app->bind(\FamilyTree365\LaravelGedcom\Models\Source::class, Source::class);

        // Register the module manager as a singleton
        $this->app->singleton(ModuleManager::class, fn ($app) => new ModuleManager);

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
        // Livewire::component('descendant-chart-component', DescendantChartComponent::class);
        // Livewire::component('people-search', PeopleSearch::class);
        // Livewire::component('pedigree-chart', PedigreeChart::class);
        // Livewire::component('create-team', CreateTeam::class);
        // Livewire::component('edit-profile', EditProfile::class);
        // Load any Filament resource traits early so that resources may use
        // them without relying on Composer's autoloader (our environment's
        // PHP version prevents us from regenerating the optimized class map).
        foreach (glob(app_path('Filament/App/Resources/*Trait.php')) as $traitFile) {
            require_once $traitFile;
        }

        // Enable default modules on first boot
        $this->enableDefaultModules();

        $this->resetPermissionTeamBetweenJobs();
    }

    /**
     * Give every queued job a clean permission-team context, and hand back
     * whatever was there before once it finishes.
     *
     * PermissionRegistrar holds the current team on the app singleton, and its
     * only reset is registered for Octane — which a plain queue worker never
     * hits. So a long-lived worker would carry one job's team into the next, and
     * a role check in job B could resolve against team A. Each job therefore
     * starts from null; jobs that establish a team (EstablishesTeam) set their
     * own from there.
     *
     * The team held before the job is saved and restored afterwards rather than
     * simply nulled, because on the sync queue — which .env.example and the test
     * config both use — a job runs inside the web request that dispatched it.
     * Nulling and not restoring would leave the rest of that request with no
     * permission team. The save/restore is a stack so nested dispatches unwind
     * correctly.
     */
    private function resetPermissionTeamBetweenJobs(): void
    {
        $teamStack = [];

        Queue::before(function () use (&$teamStack): void {
            $registrar = app(PermissionRegistrar::class);
            $teamStack[] = $registrar->getPermissionsTeamId();
            $registrar->setPermissionsTeamId(null);
        });

        $restore = function () use (&$teamStack): void {
            if ($teamStack === []) {
                return;
            }
            app(PermissionRegistrar::class)->setPermissionsTeamId(array_pop($teamStack));
        };

        Queue::after($restore);
        Queue::failing($restore);
    }

    /**
     * Enable default modules if they haven't been enabled yet.
     */
    protected function enableDefaultModules(): void
    {
        $moduleManager = app(ModuleManager::class);
        $defaultModules = config('modules.default_enabled', []);

        foreach ($defaultModules as $moduleName) {
            if ($moduleManager->has($moduleName) && ! $moduleManager->get($moduleName)->isEnabled()) {
                try {
                    $moduleManager->enable($moduleName);
                } catch (Exception $e) {
                    // Log error but don't break the application
                    Log::warning("Failed to enable default module {$moduleName}: ".$e->getMessage());
                }
            }
        }
    }
}
