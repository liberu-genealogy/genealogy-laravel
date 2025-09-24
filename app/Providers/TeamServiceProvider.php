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
            \FamilyTree365\LaravelGedcom\Utils\BatchData::class => function ($app) {
                return new BatchData();
            },
            \FamilyTree365\LaravelGedcom\Models\Family::class => function ($app) {
                return new Family();
            },
            \FamilyTree365\LaravelGedcom\Models\FamilyEvent::class => function ($app) {
                return new FamilyEvent();
            },
            \FamilyTree365\LaravelGedcom\Models\FamilySlgs::class => function ($app) {
                return new FamilySlgs();
            },
            \FamilyTree365\LaravelGedcom\Models\Person::class => function ($app) {
                return new Person();
            },
            \FamilyTree365\LaravelGedcom\Models\PersonAsso::class => function ($app) {
                return new PersonAsso();
            },
            \FamilyTree365\LaravelGedcom\Models\PersonAlia::class => function ($app) {
                return new PersonAlia();
            },
            \FamilyTree365\LaravelGedcom\Models\PersonEvent::class => function ($app) {
                return new PersonEvent();
            },
            \FamilyTree365\LaravelGedcom\Models\Addr::class => function ($app) {
                return new Addr();
            },
            \FamilyTree365\LaravelGedcom\Models\Chan::class => function ($app) {
                return new Chan();
            },
            \FamilyTree365\LaravelGedcom\Models\Subm::class => function ($app) {
                return new Subm();
            },
        ];

        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    public function boot(): void
    {
    }
}