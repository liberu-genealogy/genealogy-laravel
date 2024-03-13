<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Livewire\Livewire;
use App\Http\Livewire\PedigreeChart;

class PedigreeChartWidget extends Widget
{
    protected static string $view = 'filament.widgets.pedigree-chart-widget';

    public function render()
    {
        return Livewire::mount(PedigreeChart::class)->html();
    }
}
