<?php

namespace App\Filament\App\Pages;

use BackedEnum;
use Filament\Pages\Page;

class PeopleDashboard extends Page
{
    protected string $view = 'filament.pages.people-dashboard';

    protected static ?string $title = 'People Dashboard';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';
}
