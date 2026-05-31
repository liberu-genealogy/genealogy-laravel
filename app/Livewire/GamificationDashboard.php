<?php

namespace App\Livewire;

use App\Models\Achievement;
use App\Services\GamificationService;
use Livewire\Component;
use Livewire\WithPagination;

class GamificationDashboard extends Component
{
    use WithPagination;

    public $activeTab = 'overview';

    public $leaderboardPeriod = 'all_time';

    public $achievementCategory = 'all';

    public $showOnlyUnlocked = false;

    protected $gamificationService;

    public function boot(GamificationService $gamificationService): void
    {
        $this->gamificationService = $gamificationService;
    }

    public function mount(): void
    {
        // Initialize component
    }

    public function setActiveTab($tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function setLeaderboardPeriod($period): void
    {
        $this->leaderboardPeriod = $period;
    }

    public function setAchievementCategory($category): void
    {
        $this->achievementCategory = $category;
        $this->resetPage();
    }

    public function toggleShowOnlyUnlocked(): void
    {
        $this->showOnlyUnlocked = ! $this->showOnlyUnlocked;
        $this->resetPage();
    }

    public function toggleLeaderboardVisibility(): void
    {
        $user = auth()->user();
        $user->update(['show_on_leaderboard' => ! $user->show_on_leaderboard]);
        $this->dispatch('leaderboard-updated');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $user = auth()->user();

        $data = [
            'user' => $user,
            'userStats' => $this->gamificationService->getUserStats($user),
        ];

        switch ($this->activeTab) {
            case 'achievements':
                $data['achievements'] = $this->getAchievements();
                break;

            case 'leaderboard':
                $data['leaderboard'] = $this->gamificationService->getLeaderboard(50, $this->leaderboardPeriod);
                break;

            case 'progress':
                $data['progress'] = $user->progress()->with('achievement')->incomplete()->get();
                break;

            default: // overview
                $data['recentAchievements'] = $user->recentAchievements(7)->with('achievement')->get();
                $data['recentPoints'] = $user->recentPoints(7)->get();
                $data['topLeaderboard'] = $this->gamificationService->getLeaderboard(5, 'all_time');
                break;
        }

        return view('livewire.gamification-dashboard', $data);
    }

    private function getAchievements()
    {
        $query = Achievement::active()->ordered();

        if ($this->achievementCategory !== 'all') {
            $query->byCategory($this->achievementCategory);
        }

        $achievements = $query->get();
        $user = auth()->user();

        return $achievements->map(function ($achievement) use ($user): array {
            $userAchievement = $user->achievements()
                ->where('achievement_id', $achievement->id)
                ->first();

            $progress = $user->progress()
                ->where('achievement_id', $achievement->id)
                ->first();

            return [
                'achievement' => $achievement,
                'unlocked' => $userAchievement !== null,
                'unlocked_at' => $userAchievement?->unlocked_at,
                'progress' => $progress,
                'progress_percentage' => $progress ? $progress->getProgressPercentage() : 0,
            ];
        })->when($this->showOnlyUnlocked, fn($collection) => $collection->filter(fn ($item) => $item['unlocked']));
    }

    public function getAchievementCategories(): array
    {
        return [
            'all' => 'All Categories',
            'milestone' => 'Milestones',
            'research' => 'Research',
            'general' => 'General',
            'social' => 'Social',
        ];
    }

    public function getLeaderboardPeriods(): array
    {
        return [
            'all_time' => 'All Time',
            'month' => 'This Month',
            'week' => 'This Week',
            'today' => 'Today',
        ];
    }
}
