<?php

namespace App\Filament\App\Pages;

use UnitEnum;
use BackedEnum;
use Filament\Pages\Page;
use Livewire\Livewire;

class DabovilleReportPage extends Page
{
    protected string $view = 'd-aboville-report-page';

    protected static ?string $title = 'DAboville Report';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static UnitEnum|string|null $navigationGroup = 'Reports';

    public function mount(): void
    {
        // Livewire::mount(static::$view);
    }
}
