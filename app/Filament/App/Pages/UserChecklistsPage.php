<?php

namespace App\Filament\App\Pages;

use App\Models\UserChecklist;
use Filament\Pages\Page;

class UserChecklistsPage extends Page
{
    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    #[\Override]
    protected string $view = 'filament.app.pages.user-checklists-page';

    #[\Override]
    protected static ?string $navigationLabel = 'My Checklists';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '📋 Research Management';

    #[\Override]
    protected static ?int $navigationSort = 2;

    #[\Override]
    protected static ?string $title = 'My Research Checklists';

    public static function getNavigationBadge(): ?string
    {
        return UserChecklist::where('user_id', auth()->id())
            ->where('status', '!=', 'completed')
            ->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $overdueCount = UserChecklist::where('user_id', auth()->id())
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->count();

        return $overdueCount > 0 ? 'danger' : 'primary';
    }
}
