<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use FamilyTree365\LaravelGedcom\Utils\GedcomParser;
use FamilyTree365\LaravelGedcom\Utils\GedcomXParser;

/**
 * Replaces the vendor's FamilyTree365\LaravelGedcom\ServiceProvider so that the
 * vendor's incremental migrations (which conflict with the app's comprehensive
 * people-table migration) are never loaded.  All vendor commands and singletons
 * are still registered here.
 */
class LaravelGedcomServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \FamilyTree365\LaravelGedcom\Commands\GedcomImporter::class,
                \FamilyTree365\LaravelGedcom\Commands\GedcomExporter::class,
                \FamilyTree365\LaravelGedcom\Commands\GedcomXImporter::class,
                \FamilyTree365\LaravelGedcom\Commands\GedcomXImporterOptimized::class,
            ]);
        }

        // Intentionally NOT calling $this->loadMigrationsFrom() here.
        // The application ships its own comprehensive `people` table migration
        // that already includes every column the vendor would add incrementally.
    }

    public function register(): void
    {
        $this->app->singleton('gedcom-parser', fn($app) => new GedcomParser());
        $this->app->singleton('gedcomx-parser', fn($app) => new GedcomXParser());
        $this->app->alias('gedcom-parser', 'GedcomParser');
        $this->app->alias('gedcomx-parser', 'GedcomXParser');
    }
}
