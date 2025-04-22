<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class PeopleDashboard extends Page
{
    protected static string $view = 'filament.pages.people-dashboard';

    protected static ?string $title = 'People Dashboard';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
}
