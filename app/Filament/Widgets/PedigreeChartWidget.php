<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Person;

/**
 * Class PedigreeChartWidget is responsible for handling the display of a pedigree chart in the genealogy application.
 * It utilizes Laravel Filament and Livewire for rendering the chart based on the Person model.
 */
class PedigreeChartWidget extends Widget
{
    protected static string $view = 'livewire.pedigree-chart';

    public $persons;

    /**
     * The mount method is called upon the component's initialization. It is responsible for loading all Person models from the database and assigning them to the 'persons' property for use in the widget.
     */
    public function mount()
    {
        $this->persons = Person::all();
    }

    /**
     * The render method is responsible for rendering the Livewire view associated with the PedigreeChartWidget. It passes the 'persons' property to the view for display.
     */
    public function render()
    {
        return view(static::$view, ['persons' => $this->persons]);
    }
}
