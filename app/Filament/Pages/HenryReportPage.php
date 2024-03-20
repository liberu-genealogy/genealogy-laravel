<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class HenryReportPage extends CustomFilamentBasePage
{
    protected static string $view = 'livewire.henry-report';
    protected static ?string $title = 'Henry Report';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public function getTitle(): string
    {
        return static::$title;
    }

    public static function getNavigationIcon(): string
    {
        return static::$navigationIcon;
    }

    public function mount(): void
    {
        Livewire::mount(static::$view);
    }
}
