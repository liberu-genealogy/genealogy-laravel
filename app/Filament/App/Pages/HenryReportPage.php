<?php

namespace App\Filament\App\Pages;

use UnitEnum;
use BackedEnum;
use Filament\Pages\Page;

class HenryReportPage extends Page
{
    protected string $view = 'henry-report-page';

    protected static ?string $title = 'Henry Report';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static UnitEnum|string|null $navigationGroup = 'Reports';
}
