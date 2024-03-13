<?php

namespace App\Filament\Pages;

use Livewire\Livewire;

class DeVilliersReportPage extends CustomFilamentBasePage
{
    protected static string $view = 'livewire.devilliers-report';
    protected static ?string $title = 'DeVilliers Report';
    protected static ?string $navigationIcon = 'heroicon-o-document-report';

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
        Livewire::mount(static::$view);
    }
}
