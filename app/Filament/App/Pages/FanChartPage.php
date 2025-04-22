<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\FanChartWidget;
use Filament\Pages\Page;

class FanChartPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.fan-chart';

    protected static bool $shouldRegisterNavigation = false;

    #[\Override]
    public function getHeading(): string
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
