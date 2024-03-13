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
/**
 * Class EnsureLivewireComponentsAreRegistered
 * 
 * Ensures that all Livewire components are registered with the application.
 * This is crucial for the initialization and proper functioning of Livewire components within the genealogy application.
 */
    /**
     * Checks and registers Livewire components if they are not already registered.
     * 
     * This method iterates through a predefined list of Livewire components and registers them
     * with the application if they have not been registered already. This ensures that all necessary
     * Livewire components are available for use throughout the application.
     */
