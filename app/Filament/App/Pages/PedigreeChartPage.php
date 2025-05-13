<?php

namespace App\Filament\App\Pages;

use UnitEnum;
use BackedEnum;
use Filament\Pages\Page;

class PedigreeChartPage extends Page
{
    protected string $view = 'pedigree-chart-page';

    protected static ?string $resource = null;

    protected static ?string $title = ' Pedigree Chart';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static UnitEnum|string|null $navigationGroup = 'Charts';
}
