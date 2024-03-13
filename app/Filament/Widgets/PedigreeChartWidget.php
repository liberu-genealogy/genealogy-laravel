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

    public function mount()
    {
        $this->persons = Person::all();
    }

    public function render()
    {
        return view(static::$view, ['persons' => $this->persons]);
    }
}
    {
        return view(static::$view, ['persons' => $this->persons]);
    }
}
    {
        return view(static::$view, ['persons' => $this->persons]);
    }
}
