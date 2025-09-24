<?php

namespace App\Filament\Widgets;

use Override;
use Illuminate\Contracts\View\View;
use App\Models\Person;
use Filament\Widgets\Widget;

class PedigreeChartWidget extends Widget
{
    protected string $view = 'filament.widgets.pedigree-chart-widget';

    public function getData(): array
    {
        return [
            'people' => Person::with('parents')->get(),
        ];
    }

    #[Override]
    public function render(): View
    {
        return view(static::$view, $this->getData());
    }

    public function initializeChart(): void
    {
        $this->dispatchBrowserEvent('initializeChart', ['people' => $this->getData()['people']->toJson()]);
    }

    public function zoomIn(): void
    {
        $this->dispatchBrowserEvent('zoomIn');
    }

    public function zoomOut(): void
    {
        $this->dispatchBrowserEvent('zoomOut');
    }

    public function pan($direction): void
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