<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class PedigreeChartPage extends Page
{
    protected static string $view = 'pedigree-chart-page';

    protected static ?string $resource = null;

    protected static ?string $title = ' Pedigree Chart';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Charts';
}
