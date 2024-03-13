<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\Livewire;

class FanChartPage extends Page
{
    protected static string $view = 'livewire.fan-chart';

    protected static ?string $resource = null;

    protected ?string $title = 'Fan Chart';
    
    /**
     * Retrieves the title of the fan chart page.
     *
     * @return string The title of the page.
     */

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    public function getTitle(): string
    {
        return static::$title;
    }

    /**
     * Returns the navigation icon for the page.
     *
     * @return string The navigation icon.
     */
    public static function getNavigationIcon(): string
    {
        return static::$navigationIcon;
    }

    /**
     * Mounts the Livewire component necessary for the fan chart.
     * This function is automatically called by Livewire during the component/page lifecycle.
     */
    public function mount(): void
    {
        Livewire::mount(\App\Http\Livewire\FanChart::class);
    }
}
