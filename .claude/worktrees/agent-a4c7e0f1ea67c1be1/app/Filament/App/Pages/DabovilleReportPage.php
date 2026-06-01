<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use UnitEnum;
use BackedEnum;
use Filament\Pages\Page;
use Livewire\Livewire;

class DabovilleReportPage extends Page
{
    #[\Override]
    protected string $view = 'd-aboville-report-page';

    #[\Override]
    protected static ?string $title = 'DAboville Report';

    #[\Override]
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';
    #[\Override]
    protected static ?string $navigationLabel = 'DAboville Report';
    #[\Override]
    protected static string | \UnitEnum | null $navigationGroup = '📄 Reports';

    public function mount(): void
    {
        // Livewire::mount(static::$view);
    }
}
