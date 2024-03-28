<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PedigreeChartPage extends Page
{
    protected static string $view = 'filament.pages.pedigree-chart';

    protected static ?string $resource = null;

    protected static ?string $title = ' Family Tree Report';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public function getTitle(): string
    {
        return static::$title;
    }

    public static function getNavigationIcon(): string
    {
        return static::$navigationIcon;
    }
}
