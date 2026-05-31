<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class GamificationPage extends Page
{
    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-trophy';

    #[\Override]
    protected string $view = 'filament.app.pages.gamification-page';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '🎮 Gamification';

    #[\Override]
    protected static ?string $title = 'Achievements & Progress';

    #[\Override]
    protected static ?string $navigationLabel = 'Achievements';

    #[\Override]
    protected static ?int $navigationSort = 1;

    #[\Override]
    public function getTitle(): string
    {
        return 'Gamification Dashboard';
    }

    #[\Override]
    public function getHeading(): string
    {
        return 'Your Genealogy Journey';
    }

    #[\Override]
    public function getSubheading(): ?string
    {
        return 'Track your research progress, unlock achievements, and compete on leaderboards';
    }
}
