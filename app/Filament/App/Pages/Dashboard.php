<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget\Stat;
use UnitEnum;
use BackedEnum;
class Dashboard extends BaseDashboard
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-home';
    protected static string | UnitEnum | null $navigationGroup = 'Dashboard';
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

    public function getColumns(): int | array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }
}
