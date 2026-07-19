<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class HenryReportPage extends Page
{
    #[\Override]
    protected string $view = 'henry-report-page';

    #[\Override]
    protected static ?string $title = 'Henry Report';

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    #[\Override]
    protected static ?string $navigationLabel = 'Henry Report';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '📊 Charts & Reports';
}
