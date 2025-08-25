<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Dashboard';
    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        return [
            \App\Filament\App\Widgets\FamilyStatsWidget::class,
            \App\Filament\App\Widgets\RecentActivityWidget::class,
            \App\Filament\App\Widgets\QuickActionsWidget::class,
            \App\Filament\App\Widgets\FamilyTreeOverviewWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }
}