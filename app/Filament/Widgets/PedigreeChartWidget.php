<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Person;

class PedigreeChartWidget extends Widget
{
    protected static string $view = 'livewire.pedigree-chart';

    public $persons;

    public function mount()
    {
        $this->persons = Person::all();
    }

    /**
     * Renders the Pedigree Chart widget view.
     * 
     * This function prepares the data for the Pedigree Chart widget and returns the view to be rendered.
     * 
     * @return \Illuminate\Contracts\View\View The view instance for the Pedigree Chart widget.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view(static::$view, ['persons' => $this->persons]);
    }
}
