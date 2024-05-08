<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class NewFanChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Fan Chart';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Fan Chart',
                    'data'  => [0, 4, 6, 7, 2, 3, 5, 3, 9 , 4],
                ],
            ],
            'labels' => ["Main Person", "Parents", "Grandparents", "Great-Grandparents", "2nd Great-Grandparents", "3rd Great-Grandparents", "4th Great-Grandparents", "5th Great-Grandparents", "6th Great-Grandparents"]

        ];
    }

    protected function getType(): string
    {
        return 'radar';
    }
}
