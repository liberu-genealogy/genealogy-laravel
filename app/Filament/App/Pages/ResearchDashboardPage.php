<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class ResearchDashboardPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.app.pages.research-dashboard-page';

    protected static ?string $navigationLabel = 'Research Dashboard';

    protected static ?string $navigationGroup = '📋 Research Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Research Dashboard';

    public static function getNavigationBadge(): ?string
    {
        $overdueCount = \App\Models\UserChecklist::where('user_id', auth()->id())
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->count();

        return $overdueCount > 0 ? (string) $overdueCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}