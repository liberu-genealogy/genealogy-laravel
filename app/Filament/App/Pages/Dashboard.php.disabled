<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.app.pages.dashboard';

    public function getTitle(): string
    {
        return 'Dashboard';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\App\Widgets\SocialLinksWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\App\Widgets\PeopleWidget::class,
            \App\Filament\App\Widgets\PedigreeChartWidget::class,
            \App\Filament\App\Widgets\FanChartWidget::class,
            \App\Filament\App\Widgets\DescendantChartWidget::class,
        ];
    }
}