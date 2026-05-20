<?php

namespace App\Filament\App\Widgets;

use Override;
use Illuminate\Contracts\View\View;
use App\Models\Person;
use Filament\Widgets\Widget;

class FanChart extends Widget
{
    protected string $view = 'filament.widgets.fan-chart-widget';

    public function getData(): array
    {
        return [
            'people' => Person::all(), // Fetch all people/person data. Adjust query as needed for performance or specific requirements.
        ];
    }

    #[Override]
    public function render(): View
    {
        return view(static::$view, $this->getData());
    }
}