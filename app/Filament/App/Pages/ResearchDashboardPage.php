<?php

namespace App\Filament\App\Pages;

use App\Models\UserChecklist;
use Filament\Pages\Page;

class ResearchDashboardPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';

    protected string $view = 'filament.app.pages.research-dashboard-page';

    protected static ?string $navigationLabel = 'Research Dashboard';

    protected static string | \UnitEnum | null $navigationGroup = '📋 Research Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Research Dashboard';

    public static function getNavigationBadge(): ?string
    {
        $overdueCount = UserChecklist::where('user_id', auth()->id())
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