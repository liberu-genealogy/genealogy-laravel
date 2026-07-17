<?php

namespace App\Filament\App\Widgets;

use App\Models\Person;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Override;

class PedigreeChartWidget extends Widget
{
    #[Override]
    protected string $view = 'filament.widgets.pedigree-chart-widget';

    public function getData(): array
    {
        return [
            // parents() is a helper that walks childInFamily->husband/wife, not a
            // relation — with('parents') threw "Collection::addEagerConstraints does
            // not exist" on every render. Eager-load what it actually reads.
            'people' => Person::with(['childInFamily.husband', 'childInFamily.wife'])->get(),
        ];
    }

    #[Override]
    public function render(): View
    {
        return view(static::$view, $this->getData());
    }

    public function initializeChart(): void
    {
        $this->dispatch('initializeChart', people: $this->getData()['people']->toJson());
    }

    public function zoomIn(): void
    {
        $this->dispatch('zoomIn');
    }

    public function zoomOut(): void
    {
        $this->dispatch('zoomOut');
    }

    public function pan($direction): void
    {
        $this->dispatch('pan', direction: $direction);
    }

    #[Override]
    protected function getListeners()
    {
        return [
            'zoomIn' => 'zoomIn',
            'zoomOut' => 'zoomOut',
            'pan' => 'pan',
        ];
    }
}
