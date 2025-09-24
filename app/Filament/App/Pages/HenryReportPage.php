<?php

namespace App\Filament\App\Pages;

use UnitEnum;
use BackedEnum;
use Filament\Pages\Page;

class HenryReportPage extends Page
{
    protected string $view = 'henry-report-page';

    protected static ?string $title = 'Henry Report';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';

    protected static string | \UnitEnum | null $navigationGroup = 'Reports';
}
