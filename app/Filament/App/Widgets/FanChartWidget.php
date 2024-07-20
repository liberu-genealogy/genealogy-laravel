<?php

namespace App\Filament\App\Widgets;

namespace App\Http\Livewire;

use App\Models\Person;
use Filament\Widgets\Widget;

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
