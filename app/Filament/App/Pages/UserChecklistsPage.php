<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class UserChecklistsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string $view = 'filament.app.pages.user-checklists-page';

    protected static ?string $navigationLabel = 'My Checklists';

    protected static ?string $navigationGroup = '📋 Research Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'My Research Checklists';

    public static function getNavigationBadge(): ?string
    {
        return \App\Models\UserChecklist::where('user_id', auth()->id())
            ->where('status', '!=', 'completed')
            ->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $overdueCount = \App\Models\UserChecklist::where('user_id', auth()->id())
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->count();

        return $overdueCount > 0 ? 'danger' : 'primary';
    }
}