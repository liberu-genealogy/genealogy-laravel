<?php

namespace App\Filament\App\Pages;

use UnitEnum;
use BackedEnum;
use App\Filament\App\Widgets\FanChartWidget;
use Filament\Pages\Page;

class FanChartPage extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-chart-pie';

    protected static string | UnitEnum | null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 0;

    protected string $view = 'filament.pages.fan-chart';

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
