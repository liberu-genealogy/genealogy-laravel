<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\Livewire;

class FanChartPage extends Page
{
    protected static string $view = 'filament.pages.fan-chart';

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 0;

    protected static string $title = 'Fan Chart';

    public function mount(): void
    {
        Livewire::mount(\App\Http\Livewire\FanChart::class);
    }

    protected function getHeading(): string
    {
        return static::$title;
    }
}
