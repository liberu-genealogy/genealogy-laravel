<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Http\Livewire\DevilliersReport;
use \App\Http\Livewire\DescendantChartComponent;
use App\Http\Livewire\PedigreeChart;
use App\Http\Livewire\PeopleSearch;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

 // Add this line to import the Livewire component

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Livewire components here
        Livewire::component('devilliers-report', DevilliersReport::class);
        Livewire::component('descendant-chart-component', DescendantChartComponent::class);
        Livewire::component('people-search', PeopleSearch::class) ; // Register the new Livewire component
        Livewire::component('pedigree-chart', PedigreeChart::class) ;

        
    }
}
