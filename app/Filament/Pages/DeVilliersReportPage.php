<?php

namespace App\Filament\Pages;

use Livewire\Livewire;
use App\Filament\Pages\CustomFilamentBasePage;

class DeVilliersReportPage extends CustomFilamentBasePage
{
    protected static string $view = 'livewire.devilliers-report';
    protected static ?string $title = 'People Report';
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
        Livewire::mount('DeVilliersReportWidget');
    }
}
