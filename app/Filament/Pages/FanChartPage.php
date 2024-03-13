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

    public function getNavigationIcon(): string
    {
        return static::$navigationIcon;
    }

    public function mount(): void
    {
        Livewire::mount(\App\Http\Livewire\FanChart::class);
    }
}
/**
 * FanChartPage Class
 *
 * Represents a Filament page for displaying fan charts within the genealogy application.
 * Utilizes Livewire for dynamic data binding and updates.
 */
    /**
     * Retrieves the title of the fan chart page.
     *
     * @return string The title of the page.
     */
    /**
     * Retrieves the navigation icon class for the fan chart page.
     *
     * @return string The class name of the navigation icon.
     */
