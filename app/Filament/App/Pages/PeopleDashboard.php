<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use App\Filament\App\Widgets\PeopleWidget;

class PeopleDashboard extends Page
{
    protected static string $view = 'filament.pages.people-dashboard';

    protected static ?string $title = 'People Dashboard';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected function getHeaderWidgets(): array
    {
        return [
            PeopleWidget::class,
        ];
    }
}
