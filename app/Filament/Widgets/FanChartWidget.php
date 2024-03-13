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

    public function render()
    {
        return view(static::$view, ['people' => $this->people]);
    }
}
