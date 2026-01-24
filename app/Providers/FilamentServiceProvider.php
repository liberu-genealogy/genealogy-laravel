<?php

namespace App\Providers;

use App\Livewire\DabovilleReport;
use App\Livewire\DescendantChartComponent;
use App\Livewire\HenryReport;
use App\Livewire\PedigreeChart;
use App\Livewire\PeopleSearch;
use App\Http\Livewire\TimelineComponent; // <- add this import
use Filament\Panel;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Panel::registerLivewireComponent('example-component', ExampleComponent::class);
        // Panel::registerLivewireComponent('another-component', AnotherComponent::class);
        Panel::registerLivewireComponent('henry-report', HenryReport::class);
        Panel::registerLivewireComponent('descendant-chart-component', DescendantChartComponent::class);
        Panel::registerLivewireComponent('devilliers-report', DabovilleReport::class);
        Panel::registerLivewireComponent('people-search', PeopleSearch::class);
        Panel::registerLivewireComponent('pedigree-chart', PedigreeChart::class);

        // Register the timeline component (Filament v4)
        Panel::registerLivewireComponent('timeline', TimelineComponent::class);
    }
}
