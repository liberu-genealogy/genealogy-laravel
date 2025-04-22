<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class HenryReportPage extends Page
{
    protected static string $view = 'henry-report-page';

    protected static ?string $title = 'Henry Report';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Reports';
}
