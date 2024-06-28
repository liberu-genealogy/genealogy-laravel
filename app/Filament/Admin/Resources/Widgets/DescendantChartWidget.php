<?php

namespace App\Filament\Widgets;

use App\Models\Person;
use Filament\Widgets\Widget;

class DescendantChartWidget extends Widget
{
    protected static string $view = 'filament.widgets.descendant-chart-widget';

    public function getData(): array
    {
        $rawData = Person::all()->toArray();
        $descendantsData = $this->processDescendantData($rawData);

        return [
            'descendantsData' => $descendantsData,
        ];
    }

    private function processDescendantData($data)
    {
        return array_map(function ($item) {
            return [
                'id'   => $item['id'],
                'name' => $item['name'],
            ];
        }, $data);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view(static::$view, $this->getData());
    }
}
