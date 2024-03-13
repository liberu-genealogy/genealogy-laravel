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

    /**
     * Retrieves the title of the fan chart page.
     *
     * @return string The title of the page.
     */
    public function getTitle(): string
    {
        return static::$title;
    }

    public static function getNavigationIcon(): string
    {
        return $this->navigationIcon;
    }

    public function mount(): void
    {
        Livewire::mount(\App\Http\Livewire\FanChart::class);
    }
}
    public function mount(): void
    {
        Livewire::mount(\App\Http\Livewire\FanChart::class);
    }
}
