<?php

namespace App\Filament\Widgets;

use App\Models\Person;
use Filament\Widgets\Widget;

class FanChart extends Widget
{
    protected static string $view = 'filament.widgets.fan-chart-widget';

    public function getData(): array
    {
        return [
            'people' => Person::all(), // Fetch all people/person data. Adjust query as needed for performance or specific requirements.
        ];
    }

    #[\Override]
    public function render(): \Illuminate\Contracts\View\View
    {
        return view(static::$view, $this->getData());
    }
}
