<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class NewDescendantChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Descendant Chart';

    protected function getData(): array
{
    return [
        'datasets' => [
            [
                'label' => 'Descendant Charts',
                'data'  => [0, 4, 5, 6, 7, 3, 3, 5, 3, 3],
            ],
        ],
        'labels' => ["Grandparent", "Parent", "Child", "Grandchild", "Sibling"],
    ];
}


    protected function getType(): string
    {
        return 'line';
    }
}