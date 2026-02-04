<?php

namespace App\Http\Livewire;

use App\Models\Achievement;
use App\Models\User;
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

    public function boot(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    public function mount()
    {
        // Initialize component
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function setLeaderboardPeriod($period)
    {
        $this->leaderboardPeriod = $period;
    }

    public function setAchievementCategory($category)
    {
        $this->achievementCategory = $category;
        $this->resetPage();
    }

    public function toggleShowOnlyUnlocked()
    {
        $this->showOnlyUnlocked = !$this->showOnlyUnlocked;
        $this->resetPage();
    }

    public function toggleLeaderboardVisibility()
    {
        $user = auth()->user();
        $user->update(['show_on_leaderboard' => !$user->show_on_leaderboard]);
        $this->emit('leaderboard-updated');
    }

    public function render()
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

        return $achievements->map(function ($achievement) use ($user) {
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
        })->when($this->showOnlyUnlocked, function ($collection) {
            return $collection->filter(fn($item) => $item['unlocked']);
        });
    }

    public function getAchievementCategories()
    {
        return [
            'all' => 'All Categories',
            'milestone' => 'Milestones',
            'research' => 'Research',
            'general' => 'General',
            'social' => 'Social',
        ];
    }

    public function getLeaderboardPeriods()
    {
        return [
            'all_time' => 'All Time',
            'month' => 'This Month',
            'week' => 'This Week',
            'today' => 'Today',
        ];
    }
}

