<?php

namespace App\Filament\App\Widgets;

use App\Models\Person;
use Filament\Widgets\Widget;

class FanChartWidget extends Widget
{
    protected string $view = 'livewire.fan-chart-component';

    public $people;

    public function mount(): void
    {
        $this->people = Person::all();
    }

    #[\Override]
    public function render(): \Illuminate\Contracts\View\View
    {
        return view($this->view, ['people' => $this->people]);
    }
}
