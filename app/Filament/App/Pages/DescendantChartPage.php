<?php

namespace App\Filament\App\Pages;

use UnitEnum;
use BackedEnum;
use Filament\Pages\Page;

class DescendantChartPage extends Page
{
    protected string $view = 'descendant-chart-page';

    protected static ?string $title = 'Descendant Chart';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static UnitEnum|string|null $navigationGroup = 'Charts';
}
