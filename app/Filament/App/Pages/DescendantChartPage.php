<?php

namespace App\Filament\App\Pages;

use UnitEnum;
use BackedEnum;
use Filament\Pages\Page;

class DescendantChartPage extends Page
{
    protected string $view = 'descendant-chart-page';

    protected static ?string $title = 'Descendant Chart';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';

    protected static string | UnitEnum | null $navigationGroup = 'Charts';
}
