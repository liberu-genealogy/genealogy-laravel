<?php

namespace App\Providers;

use App\Http\Livewire\AnotherComponent;
use App\Http\Livewire\ExampleComponent;
use Filament\Panel;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Panel::registerLivewireComponent('example-component', ExampleComponent::class);
        Panel::registerLivewireComponent('another-component', AnotherComponent::class);
    }
}
