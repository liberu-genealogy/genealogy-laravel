<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class NewHenryReportWidget extends ChartWidget
{
    protected static ?string $heading = 'Henry Chart';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Henry Chart',
                    'data'  => [0, 4, 6, 7, 2, 3, 5, 3, 9 , 4],
                ],
            ],
            'labels' => ["Generation", "Name", "Birth Date", "Death Date", "Parent", "Child", "Spouse"]


        ];
    }

    protected function getType(): string
    {
        return 'bubble';
    }
}
