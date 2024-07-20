<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Livewire\Livewire;

class DabovilleReportPage extends Page
{
    protected static string $view = 'd-aboville-report-page';

    protected static ?string $title = 'DAboville Report';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Reports';

    public function mount(): void
    {
        // Livewire::mount(static::$view);
    }
}
