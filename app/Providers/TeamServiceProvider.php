<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Addr;
use App\Models\BatchData;
use App\Models\Chan;
use App\Models\Family;
use App\Models\FamilyEvent;
use App\Models\FamilySlgs;
use App\Models\Person;
use App\Models\PersonAlia;
use App\Models\PersonAsso;
use App\Models\PersonEvent;
use App\Models\Subm;
use Illuminate\Support\ServiceProvider;
use Override;

class TeamServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        // Instead of binding directly, we'll use a callback to prevent circular dependencies
        $bindings = [
            \FamilyTree365\LaravelGedcom\Utils\BatchData::class => fn ($app) => new BatchData,
            \FamilyTree365\LaravelGedcom\Models\Family::class => fn ($app) => new Family,
            \FamilyTree365\LaravelGedcom\Models\FamilyEvent::class => fn ($app) => new FamilyEvent,
            \FamilyTree365\LaravelGedcom\Models\FamilySlgs::class => fn ($app) => new FamilySlgs,
            \FamilyTree365\LaravelGedcom\Models\Person::class => fn ($app) => new Person,
            \FamilyTree365\LaravelGedcom\Models\PersonAsso::class => fn ($app) => new PersonAsso,
            \FamilyTree365\LaravelGedcom\Models\PersonAlia::class => fn ($app) => new PersonAlia,
            \FamilyTree365\LaravelGedcom\Models\PersonEvent::class => fn ($app) => new PersonEvent,
            \FamilyTree365\LaravelGedcom\Models\Addr::class => fn ($app) => new Addr,
            \FamilyTree365\LaravelGedcom\Models\Chan::class => fn ($app) => new Chan,
            \FamilyTree365\LaravelGedcom\Models\Subm::class => fn ($app) => new Subm,
        ];

        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    public function boot(): void {}
}
