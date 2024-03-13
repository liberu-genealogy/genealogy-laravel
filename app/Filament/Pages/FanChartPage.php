<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\Livewire;

class FanChartPage extends Page
{
    protected static string $view = 'livewire.fan-chart';

    protected static ?string $resource = null;

    protected static ?string $title = 'Fan Chart';

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    public function getTitle(): string
    {
        return static::$title;
    }

    /**
     * Retrieves the navigation icon for the Fan Chart page.
     *
     * @return string The navigation icon.
     */
    public static function getNavigationIcon(): string
    {
        return self::$navigationIcon;
    }

    public function mount(): void
    {
        Livewire::mount(\App\Http\Livewire\FanChart::class);
    }
}
