<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class DescendantChartPage extends Page
{
    protected static string $view = 'descendant-chart-page';

    protected static ?string $title = 'Descendant Chart';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Charts';
}
