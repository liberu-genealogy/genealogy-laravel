<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class HenryReportPage extends CustomFilamentBasePage
{
    protected static string $view = 'new-view-name';
    protected static ?string $title = 'Henry Report';
    protected static ?string $navigationIcon = "heroicon-o-document-report";

    public function getTitle(): string
    {
        return static::$title;
    }

    public static function getNavigationIcon(): string
    {
        return static::$navigationIcon;
    }

    public function render(): \Illuminate\Contracts\Support\Renderable
    {
        return \Livewire::mount(static::$view);
    }

    public function mount(): void
    {
        \Livewire::mount(static::$view);
    }
}
