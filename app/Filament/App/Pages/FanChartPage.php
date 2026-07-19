<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class FanChartPage extends Page
{
    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-pie';

    #[\Override]
    protected string $view = 'filament.app.pages.fan-chart-page';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '📊 Charts & Reports';

    #[\Override]
    protected static ?string $title = 'Fan Chart';

    #[\Override]
    protected static ?string $navigationLabel = 'Fan Chart';

    #[\Override]
    protected static ?int $navigationSort = 2;

    #[\Override]
    public function getTitle(): string
    {
        return 'Fan Chart - Ancestor Visualization';
    }

    #[\Override]
    public function getHeading(): string
    {
        return 'Fan Chart';
    }

    #[\Override]
    public function getSubheading(): ?string
    {
        return 'Visualize your ancestors in a circular fan layout';
    }
}
