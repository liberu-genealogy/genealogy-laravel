<?php

namespace App\Filament\Widgets;

use App\Models\Person;
use Filament\Widgets\Widget;

class PedigreeChartWidget extends Widget
{
    protected static string $view = 'filament.widgets.pedigree-chart-widget';

    public function getData(): array
    {
        return [
            'people' => Person::with('parents')->get(),
        ];
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view(static::$view, $this->getData());
    }

    public function initializeChart()
    {
        $this->dispatchBrowserEvent('initializeChart', ['people' => $this->getData()['people']->toJson()]);
    }

    public function zoomIn()
    {
        $this->dispatchBrowserEvent('zoomIn');
    }

    public function zoomOut()
    {
        $this->dispatchBrowserEvent('zoomOut');
    }

    public function pan($direction)
    {
        $this->dispatchBrowserEvent('pan', ['direction' => $direction]);
    }

    protected function getListeners()
    {
        return [
            'zoomIn'  => 'zoomIn',
            'zoomOut' => 'zoomOut',
            'pan'     => 'pan',
        ];
    }
}
