<?php

declare(strict_types=1);

namespace App\Providers;

use Override;
use Illuminate\Support\ServiceProvider;
use App\Models\{Addr, BatchData, Chan, Family, FamilyEvent, FamilySlgs,
    Person, PersonAlia, PersonAsso, PersonEvent, Subm};

class TeamServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        // Instead of binding directly, we'll use a callback to prevent circular dependencies
        $bindings = [
            \FamilyTree365\LaravelGedcom\Utils\BatchData::class => fn($app) => new BatchData(),
            \FamilyTree365\LaravelGedcom\Models\Family::class => fn($app) => new Family(),
            \FamilyTree365\LaravelGedcom\Models\FamilyEvent::class => fn($app) => new FamilyEvent(),
            \FamilyTree365\LaravelGedcom\Models\FamilySlgs::class => fn($app) => new FamilySlgs(),
            \FamilyTree365\LaravelGedcom\Models\Person::class => fn($app) => new Person(),
            \FamilyTree365\LaravelGedcom\Models\PersonAsso::class => fn($app) => new PersonAsso(),
            \FamilyTree365\LaravelGedcom\Models\PersonAlia::class => fn($app) => new PersonAlia(),
            \FamilyTree365\LaravelGedcom\Models\PersonEvent::class => fn($app) => new PersonEvent(),
            \FamilyTree365\LaravelGedcom\Models\Addr::class => fn($app) => new Addr(),
            \FamilyTree365\LaravelGedcom\Models\Chan::class => fn($app) => new Chan(),
            \FamilyTree365\LaravelGedcom\Models\Subm::class => fn($app) => new Subm(),
        ];

        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    public function boot(): void
    {
    }
}