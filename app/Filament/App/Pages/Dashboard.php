<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\FamilyStatsWidget;
use App\Filament\App\Widgets\RecentActivityWidget;
use App\Filament\App\Widgets\QuickActionsWidget;
use App\Filament\App\Widgets\FamilyTreeOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Dashboard extends BaseDashboard
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';
    protected static string | \UnitEnum | null $navigationGroup = '🏠 Dashboard';
    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        return [
            FamilyStatsWidget::class,
            RecentActivityWidget::class,
            QuickActionsWidget::class,
            FamilyTreeOverviewWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }
}
