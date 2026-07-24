<?php

namespace App\Providers;

use App\Models\Family;
use App\Models\Person;
use App\Models\PersonEvent;
use FamilyTree365\LaravelGedcom\Commands\GedcomExporter;
use FamilyTree365\LaravelGedcom\Commands\GedcomImporter;
use FamilyTree365\LaravelGedcom\Commands\GedcomXImporter;
use FamilyTree365\LaravelGedcom\Commands\GedcomXImporterOptimized;
use FamilyTree365\LaravelGedcom\Utils\GedcomParser;
use FamilyTree365\LaravelGedcom\Utils\GedcomXParser;
use Illuminate\Support\ServiceProvider;

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
                GedcomImporter::class,
                GedcomExporter::class,
                GedcomXImporter::class,
                GedcomXImporterOptimized::class,
            ]);
        }

        // Intentionally NOT calling $this->loadMigrationsFrom() here.
        // The application ships its own comprehensive `people` table migration
        // that already includes every column the vendor would add incrementally.
    }

    #[\Override]
    public function register(): void
    {
        $this->app->singleton('gedcom-parser', fn ($app): GedcomParser => new GedcomParser);
        $this->app->singleton('gedcomx-parser', fn ($app): GedcomXParser => new GedcomXParser);
        $this->app->alias('gedcom-parser', 'GedcomParser');
        $this->app->alias('gedcomx-parser', 'GedcomXParser');

        // Bind vendor models to application models so that the GEDCOM parser
        // uses the app's models (which include team_id in $fillable and proper
        // type_id handling) when it calls app(Family::class) / app(Person::class).
        $this->app->bind(
            \FamilyTree365\LaravelGedcom\Models\Family::class,
            Family::class,
        );
        $this->app->bind(
            \FamilyTree365\LaravelGedcom\Models\Person::class,
            Person::class,
        );
        // The vendor PersonEvent::boot() calls static::observe() mid-boot, which
        // re-enters bootIfNotBooted and throws once the model is first touched
        // inside another operation (e.g. GedcomGenerator export after an import).
        // App\Models\PersonEvent overrides boot() to register the observer in
        // booted() instead, so route vendor app(PersonEvent::class) lookups to it.
        $this->app->bind(
            \FamilyTree365\LaravelGedcom\Models\PersonEvent::class,
            PersonEvent::class,
        );
    }
}
