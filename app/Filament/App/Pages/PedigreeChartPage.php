<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class PedigreeChartPage extends Page
{
    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    #[\Override]
    protected static ?string $navigationLabel = 'Pedigree Chart';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '📊 Charts & Visualizations';

    #[\Override]
    protected static ?int $navigationSort = 1;

    #[\Override]
    protected string $view = 'filament.app.pages.pedigree-chart';

    #[\Override]
    protected static ?string $title = 'Pedigree Chart';

    #[\Override]
    public function getTitle(): string
    {
        return 'Pedigree Chart - Ancestor Tree';
    }

    #[\Override]
    public function getHeading(): string
    {
        return 'Pedigree Chart';
    }

    #[\Override]
    public function getSubheading(): ?string
    {
        return 'Visualize your direct ancestors in a traditional pedigree format';
    }
}
