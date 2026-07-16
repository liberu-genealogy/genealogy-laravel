<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class DescendantChartPage extends Page
{
    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-pie';

    #[\Override]
    protected static ?string $navigationLabel = 'Descendant Chart';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '📊 Charts & Visualizations';

    #[\Override]
    protected static ?int $navigationSort = 3;

    #[\Override]
    protected string $view = 'filament.app.pages.descendant-chart-page';

    #[\Override]
    protected static ?string $title = 'Descendant Chart';

    #[\Override]
    public function getTitle(): string
    {
        return 'Descendant Chart - Family Tree';
    }

    #[\Override]
    public function getHeading(): string
    {
        return 'Descendant Chart';
    }

    #[\Override]
    public function getSubheading(): ?string
    {
        return 'Explore descendants and family lineages from any ancestor';
    }
}
