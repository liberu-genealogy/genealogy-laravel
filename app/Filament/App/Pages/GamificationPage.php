<?php

namespace App\Filament\App\Pages;

use App\Http\Livewire\GamificationDashboard;
use Filament\Pages\Page;

class GamificationPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static string $view = 'filament.app.pages.gamification-page';

    protected static ?string $navigationGroup = '🎮 Gamification';

    protected static ?string $title = 'Achievements & Progress';

    protected static ?string $navigationLabel = 'Achievements';

    protected static ?int $navigationSort = 1;

    public function getTitle(): string
    {
        return 'Gamification Dashboard';
    }

    public function getHeading(): string
    {
        return 'Your Genealogy Journey';
    }

    public function getSubheading(): ?string
    {
        return 'Track your research progress, unlock achievements, and compete on leaderboards';
    }
}