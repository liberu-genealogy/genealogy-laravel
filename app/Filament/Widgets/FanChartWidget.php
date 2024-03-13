<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Person;

class FanChartWidget extends Widget
{
    protected static string $view = 'livewire.fan-chart-component';

    public $people;

    public function mount()
    {
        $this->people = Person::all();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view(static::$view, ['people' => $this->people]);
    }
}
    /**
     * Renders the Fan Chart widget view.
     * 
     * This function prepares the data for the Fan Chart widget and returns the view to be rendered.
     * 
     * @return \Illuminate\Contracts\View\View The view instance for the Fan Chart widget.
     */
