<?php

namespace App\Providers;

use App\Models\{
    Addr, BatchData, Chan, Family,
    FamilyEvent, FamilySlgs, Person,
    PersonAlia, PersonAsso, PersonEvent, Subm
};
use Illuminate\Support\ServiceProvider;

final class TeamServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(\FamilyTree365\LaravelGedcom\Utils\BatchData::class, BatchData::class);
        $this->app->bind(\FamilyTree365\LaravelGedcom\Models\Family::class, Family::class);
        $this->app->bind(\FamilyTree365\LaravelGedcom\Models\FamilyEvent::class, FamilyEvent::class);
        $this->app->bind(\FamilyTree365\LaravelGedcom\Models\FamilySlgs::class, FamilySlgs::class);
        $this->app->bind(\FamilyTree365\LaravelGedcom\Models\Person::class, Person::class);
        $this->app->bind(\FamilyTree365\LaravelGedcom\Models\PersonAsso::class, PersonAsso::class);
        $this->app->bind(\FamilyTree365\LaravelGedcom\Models\PersonAlia::class, PersonAlia::class);
        $this->app->bind(\FamilyTree365\LaravelGedcom\Models\PersonEvent::class, PersonEvent::class);
        $this->app->bind(\FamilyTree365\LaravelGedcom\Models\Addr::class, Addr::class);
        $this->app->bind(\FamilyTree365\LaravelGedcom\Models\Chan::class, Chan::class);
        $this->app->bind(\FamilyTree365\LaravelGedcom\Models\Subm::class, Subm::class);
    }
}