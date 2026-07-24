<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class AhnentafelReportPage extends Page
{
    #[\Override]
    protected string $view = 'ahnentafel-report-page';

    #[\Override]
    protected static ?string $title = 'Ahnentafel Report';

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    #[\Override]
    protected static ?string $navigationLabel = 'Ahnentafel Report';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '📊 Charts & Reports';
}
