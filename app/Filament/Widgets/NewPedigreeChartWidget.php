<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class NewPedigreeChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Pedigree Chart';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Pedigree Chart',
                    'data'  => [0, 4, 5, 6, 7, 2, 3, 5, 3, 9],
                ],
            ],
            'labels' => ["Ancestor", "Progenitor", "Offspring", "Descendant", "Sibling"]
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
