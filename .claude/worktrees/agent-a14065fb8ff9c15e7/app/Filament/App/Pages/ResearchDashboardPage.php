<?php

namespace App\Filament\App\Pages;

use App\Models\UserChecklist;
use Filament\Pages\Page;

class ResearchDashboardPage extends Page
{
    #[\Override]
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';

    #[\Override]
    protected string $view = 'filament.app.pages.research-dashboard-page';

    #[\Override]
    protected static ?string $navigationLabel = 'Research Dashboard';

    #[\Override]
    protected static string | \UnitEnum | null $navigationGroup = '📋 Research Management';

    #[\Override]
    protected static ?int $navigationSort = 3;

    #[\Override]
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