<?php

namespace App\Http\Livewire;

use Filament\Panel;

class EnsureLivewireComponentsAreRegistered
{
    public static function checkAndRegisterComponents()
    {
        $components = [
            'example-component' => ExampleComponent::class,
            'another-component' => AnotherComponent::class,
        ];

        foreach ($components as $alias => $componentClass) {
            if (!Panel::isLivewireComponentRegistered($alias)) {
                Panel::registerLivewireComponent($alias, $componentClass);
            }
        }
    }
}
