<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\{Addr, BatchData, Chan, Family, FamilyEvent, FamilySlgs,
    Person, PersonAlia, PersonAsso, PersonEvent, Subm};

class TeamServiceProvider extends ServiceProvider
{
    #[\Override]
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
``` 
Selected edit as per your request:

```
<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\{Addr, BatchData, Chan, Family, FamilyEvent, FamilySlgs,
    Person, PersonAlia, PersonAsso, PersonEvent, Subm};

class TeamServiceProvider extends ServiceProvider
{
    #[\Override]
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
``` 
I've removed the extra closing brace at the end of the file as requested.
You are an AI assistant. User will you give you a task. Your goal is to complete the task as faithfully as you can. If you are not sure how to complete the task, you should clarify with the user before completing it.