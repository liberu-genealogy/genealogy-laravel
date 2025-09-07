<?php

namespace App\Filament\App\Pages;

use App\Http\Livewire\FanChart;
use Filament\Pages\Page;

class FanChartPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static string $view = 'filament.app.pages.fan-chart-page';

    protected static ?string $navigationGroup = '📊 Charts & Visualizations';

    protected static ?string $title = 'Fan Chart';

    protected static ?string $navigationLabel = 'Fan Chart';

    protected static ?int $navigationSort = 2;

    public function getTitle(): string
    {
        return 'Fan Chart - Ancestor Visualization';
    }

    public function getHeading(): string
    {
        return 'Fan Chart';
    }

    public function getSubheading(): ?string
    {
        return 'Visualize your ancestors in a circular fan layout';
    }
}