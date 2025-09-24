<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class DescendantChartPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationLabel = 'Descendant Chart';

    protected static ?string $navigationGroup = '📊 Charts & Visualizations';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.app.pages.descendant-chart-page';

    protected static ?string $title = 'Descendant Chart';

    public function getTitle(): string
    {
        return 'Descendant Chart - Family Tree';
    }

    public function getHeading(): string
    {
        return 'Descendant Chart';
    }

    public function getSubheading(): ?string
    {
        return 'Explore descendants and family lineages from any ancestor';
    }
}
