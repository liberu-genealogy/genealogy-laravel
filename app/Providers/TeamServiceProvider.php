<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\{Addr, BatchData, Chan, Family, FamilyEvent, FamilySlgs,
    Person, PersonAlia, PersonAsso, PersonEvent, Subm};

final readonly class TeamServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $bindings = [
            \FamilyTree365\LaravelGedcom\Utils\BatchData::class => BatchData::class,
            \FamilyTree365\LaravelGedcom\Models\Family::class => Family::class,
            \FamilyTree365\LaravelGedcom\Models\FamilyEvent::class => FamilyEvent::class,
            \FamilyTree365\LaravelGedcom\Models\FamilySlgs::class => FamilySlgs::class,
            \FamilyTree365\LaravelGedcom\Models\Person::class => Person::class,
            \FamilyTree365\LaravelGedcom\Models\PersonAsso::class => PersonAsso::class,
            \FamilyTree365\LaravelGedcom\Models\PersonAlia::class => PersonAlia::class,
            \FamilyTree365\LaravelGedcom\Models\PersonEvent::class => PersonEvent::class,
            \FamilyTree365\LaravelGedcom\Models\Addr::class => Addr::class,
            \FamilyTree365\LaravelGedcom\Models\Chan::class => Chan::class,
            \FamilyTree365\LaravelGedcom\Models\Subm::class => Subm::class,
        ];

        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    public function boot(): void
    {
    }
}