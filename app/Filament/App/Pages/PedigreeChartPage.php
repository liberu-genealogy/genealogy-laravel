<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class PedigreeChartPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Pedigree Chart';

    protected static string | \UnitEnum | null $navigationGroup = '📊 Charts & Visualizations';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.app.pages.pedigree-chart-page';

    protected static ?string $title = 'Pedigree Chart';

    public function getTitle(): string
    {
        return 'Pedigree Chart - Ancestor Tree';
    }

    public function getHeading(): string
    {
        return 'Pedigree Chart';
    }

    public function getSubheading(): ?string
    {
        return 'Visualize your direct ancestors in a traditional pedigree format';
    }
}
