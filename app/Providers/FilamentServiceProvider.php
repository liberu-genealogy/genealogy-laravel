<?php

namespace App\Providers;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use App\Http\Livewire\ExampleComponent;
use App\Http\Livewire\AnotherComponent;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Panel::registerLivewireComponent('example-component', ExampleComponent::class);
        Panel::registerLivewireComponent('another-component', AnotherComponent::class);
    }
}
