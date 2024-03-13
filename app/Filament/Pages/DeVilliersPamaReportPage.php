<?php

namespace App\Filament\Pages;

use Livewire\Livewire;

class DeVilliersPamaReportPage extends CustomFilamentBasePage
{
    protected static string $view = 'livewire.devilliers-pama-report';
    protected static ?string $title = 'DeVilliers/Pama Report';
    protected static ?string $navigationIcon = 'heroicon-o-document-report';

    public function getTitle(): string
    {
        return static::$title;
    }

    public static function getNavigationIcon(): string
    {
        return static::$navigationIcon;
    }
}
