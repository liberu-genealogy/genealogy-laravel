<?php

namespace App\Filament\Pages;

use Livewire\Livewire;

class AhnentafelReportPage extends CustomFilamentBasePage
{
    protected static string $view = 'livewire.ahnentafel-report';
    protected static ?string $title = 'Ahnentafel Report';
    protected static ?string $navigationIcon = 'heroicon-o-document-report';

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
