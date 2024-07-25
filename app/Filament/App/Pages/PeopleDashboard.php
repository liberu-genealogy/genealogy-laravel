<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\PeopleWidget;
use Filament\Pages\Page;

class PeopleDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static string $view = 'filament.app.pages.people-dashboard';

    public function getTitle(): string
    {
        return __('People Dashboard');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PeopleWidget::class,
        ];
    }
}
