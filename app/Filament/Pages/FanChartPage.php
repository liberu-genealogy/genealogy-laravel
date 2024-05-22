<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\FanChartWidget;
use Filament\Pages\Page;

class FanChartPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.fan-chart';

    protected function getHeading(): string
    {
        return 'Fan Chart';
    }

    protected function getWidgets(): array
    {
        return [
            FanChartWidget::class,
        ];
    }
}
