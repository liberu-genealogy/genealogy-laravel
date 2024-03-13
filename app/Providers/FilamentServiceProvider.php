<?php

/**
 * Class FilamentServiceProvider
 * 
 * Extends Laravel's service provider to incorporate functionalities specific to the Filament admin panel.
 * This includes registering custom views, components, and other necessary resources for the Filament admin panel.
 */
namespace App\Providers;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use App\Http\Livewire\ExampleComponent;
use App\Http\Livewire\AnotherComponent;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Registers custom Livewire components for the Filament admin panel.
     *
     * This method utilizes the Panel facade to register Livewire components, making them
     * available for use within the Filament admin panel. It ensures that all necessary
     * components are registered and accessible.
     *
     * @return void
     */
    public function boot()
    {
        Panel::registerLivewireComponent('example-component', ExampleComponent::class);
        Panel::registerLivewireComponent('another-component', AnotherComponent::class);
    }
}
