<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\FamilyStatsWidget;
use App\Filament\App\Widgets\FamilyTreeOverviewWidget;
use App\Filament\App\Widgets\PeopleWidget;
use App\Filament\App\Widgets\QuickActionsWidget;
use App\Filament\App\Widgets\RecentActivityWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    #[\Override]
    protected static ?string $navigationLabel = 'Dashboard';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '🏠 Dashboard';

    #[\Override]
    protected static ?int $navigationSort = 1;

    #[\Override]
    public function getWidgets(): array
    {
        return [
            FamilyStatsWidget::class,
            PeopleWidget::class,
            RecentActivityWidget::class,
            QuickActionsWidget::class,
            FamilyTreeOverviewWidget::class,
        ];
    }

    #[\Override]
    public function getColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }
}
