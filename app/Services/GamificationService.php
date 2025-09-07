<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserPoint;
use App\Models\UserProgress;
use App\Events\AchievementUnlocked;
use App\Events\UserLeveledUp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GamificationService
{
    /**
     * Award points to a user for a specific activity
     */
    public function awardPoints(
        User $user,
        string $activityType,
        int $points,
        string $description = null,
        array $metadata = [],
        $relatedModel = null
    ): UserPoint {
        return DB::transaction(function () use ($user, $activityType, $points, $description, $metadata, $relatedModel) {
            // Create the point record
            $userPoint = UserPoint::create([
                'user_id' => $user->id,
                'activity_type' => $activityType,
                'points' => $points,
                'description' => $description,
                'metadata' => $metadata,
                'related_model_id' => $relatedModel?->id,
                'related_model_type' => $relatedModel ? get_class($relatedModel) : null,
            ]);

            // Update user's total points
            $user->increment('total_points', $points);
            $user->update(['last_activity_at' => now()]);

            // Check for level up
            $user->updateLevel();

            // Check for achievement unlocks
            $this->checkAchievements($user, $activityType, $metadata);

            return $userPoint;
        });
    }

    /**
     * Check and unlock achievements for a user
     */
    public function checkAchievements(User $user, string $activityType = null, array $metadata = []): array
    {
        $unlockedAchievements = [];

        // Get all active achievements that the user hasn't unlocked yet
        $achievements = Achievement::active()
            ->whereNotIn('id', function ($query) use ($user) {
                $query->select('achievement_id')
                    ->from('user_achievements')
                    ->where('user_id', $user->id);
            })
            ->get();

        foreach ($achievements as $achievement) {
            if ($this->checkAchievementRequirements($user, $achievement, $activityType, $metadata)) {
                $unlockedAchievement = $this->unlockAchievement($user, $achievement);
                $unlockedAchievements[] = $unlockedAchievement;
            } else {
                // Update progress if not unlocked
                $this->updateAchievementProgress($user, $achievement, $activityType, $metadata);
            }
        }

        return $unlockedAchievements;
    }

    /**
     * Check if a user meets the requirements for an achievement
     */
    private function checkAchievementRequirements(
        User $user,
        Achievement $achievement,
        string $activityType = null,
        array $metadata = []
    ): bool {
        $requirements = $achievement->requirements ?? [];

        // Handle different types of achievements
        switch ($achievement->key) {
            case 'first_person_added':
                return $this->getPersonCount($user) >= 1;

            case 'family_builder':
                return $this->getPersonCount($user) >= ($requirements['count'] ?? 10);

            case 'genealogy_researcher':
                return $this->getPersonCount($user) >= ($requirements['count'] ?? 50);

            case 'family_historian':
                return $this->getPersonCount($user) >= ($requirements['count'] ?? 100);

            case 'first_family_created':
                return $this->getFamilyCount($user) >= 1;

            case 'family_connector':
                return $this->getFamilyCount($user) >= ($requirements['count'] ?? 5);

            case 'relationship_expert':
                return $this->getFamilyCount($user) >= ($requirements['count'] ?? 20);

            case 'event_chronicler':
                return $this->getEventCount($user) >= ($requirements['count'] ?? 10);

            case 'life_documenter':
                return $this->getEventCount($user) >= ($requirements['count'] ?? 50);

            case 'photo_archivist':
                return $this->getPhotoCount($user) >= ($requirements['count'] ?? 5);

            case 'memory_keeper':
                return $this->getPhotoCount($user) >= ($requirements['count'] ?? 25);

            case 'point_collector':
                return $user->total_points >= ($requirements['points'] ?? 1000);

            case 'high_achiever':
                return $user->total_points >= ($requirements['points'] ?? 5000);

            case 'legend':
                return $user->total_points >= ($requirements['points'] ?? 10000);

            case 'level_up':
                return $user->level >= ($requirements['level'] ?? 5);

            case 'experienced_researcher':
                return $user->level >= ($requirements['level'] ?? 10);

            case 'daily_researcher':
                return $this->checkDailyActivity($user, $requirements['days'] ?? 7);

            case 'dedicated_genealogist':
                return $this->checkDailyActivity($user, $requirements['days'] ?? 30);

            case 'achievement_hunter':
                return $user->achievements()->count() >= ($requirements['count'] ?? 5);

            default:
                return false;
        }
    }

    /**
     * Unlock an achievement for a user
     */
    private function unlockAchievement(User $user, Achievement $achievement): UserAchievement
    {
        return DB::transaction(function () use ($user, $achievement) {
            // Create the user achievement record
            $userAchievement = UserAchievement::create([
                'user_id' => $user->id,
                'achievement_id' => $achievement->id,
                'unlocked_at' => now(),
                'progress_data' => $this->getAchievementProgressData($user, $achievement),
            ]);

            // Award points for the achievement
            if ($achievement->points > 0) {
                $this->awardPoints(
                    $user,
                    'achievement_unlocked',
                    $achievement->points,
                    "Unlocked achievement: {$achievement->name}",
                    ['achievement_id' => $achievement->id]
                );
            }

            // Remove any existing progress tracking for this achievement
            UserProgress::where('user_id', $user->id)
                ->where('achievement_id', $achievement->id)
                ->delete();

            // Dispatch achievement unlocked event
            event(new AchievementUnlocked($user, $achievement));

            Log::info("Achievement unlocked", [
                'user_id' => $user->id,
                'achievement_key' => $achievement->key,
                'achievement_name' => $achievement->name,
                'points_awarded' => $achievement->points,
            ]);

            return $userAchievement;
        });
    }

    /**
     * Update achievement progress for a user
     */
    private function updateAchievementProgress(
        User $user,
        Achievement $achievement,
        string $activityType = null,
        array $metadata = []
    ): void {
        $currentProgress = $this->calculateCurrentProgress($user, $achievement);
        $targetProgress = $this->getTargetProgress($achievement);

        if ($targetProgress > 0) {
            UserProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->id,
                ],
                [
                    'current_progress' => $currentProgress,
                    'target_progress' => $targetProgress,
                    'progress_data' => $this->getAchievementProgressData($user, $achievement),
                    'started_at' => UserProgress::where('user_id', $user->id)
                        ->where('achievement_id', $achievement->id)
                        ->value('started_at') ?? now(),
                    'last_updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Calculate current progress for an achievement
     */
    private function calculateCurrentProgress(User $user, Achievement $achievement): int
    {
        switch ($achievement->key) {
            case 'family_builder':
            case 'genealogy_researcher':
            case 'family_historian':
                return $this->getPersonCount($user);

            case 'family_connector':
            case 'relationship_expert':
                return $this->getFamilyCount($user);

            case 'event_chronicler':
            case 'life_documenter':
                return $this->getEventCount($user);

            case 'photo_archivist':
            case 'memory_keeper':
                return $this->getPhotoCount($user);

            case 'point_collector':
            case 'high_achiever':
            case 'legend':
                return $user->total_points;

            case 'level_up':
            case 'experienced_researcher':
                return $user->level;

            case 'daily_researcher':
                return $this->getDailyActivityStreak($user);

            case 'dedicated_genealogist':
                return $this->getDailyActivityStreak($user);

            case 'achievement_hunter':
                return $user->achievements()->count();

            default:
                return 0;
        }
    }

    /**
     * Get target progress for an achievement
     */
    private function getTargetProgress(Achievement $achievement): int
    {
        $requirements = $achievement->requirements ?? [];

        return $requirements['count'] ?? $requirements['points'] ?? $requirements['level'] ?? $requirements['days'] ?? 1;
    }

    /**
     * Get achievement progress data
     */
    private function getAchievementProgressData(User $user, Achievement $achievement): array
    {
        return [
            'person_count' => $this->getPersonCount($user),
            'family_count' => $this->getFamilyCount($user),
            'event_count' => $this->getEventCount($user),
            'photo_count' => $this->getPhotoCount($user),
            'total_points' => $user->total_points,
            'level' => $user->level,
            'achievement_count' => $user->achievements()->count(),
            'daily_streak' => $this->getDailyActivityStreak($user),
        ];
    }

    /**
     * Get person count for user
     */
    private function getPersonCount(User $user): int
    {
        return \App\Models\Person::where('team_id', $user->current_team_id ?? $user->latestTeam?->id)->count();
    }

    /**
     * Get family count for user
     */
    private function getFamilyCount(User $user): int
    {
        return \App\Models\Family::where('team_id', $user->current_team_id ?? $user->latestTeam?->id)->count();
    }

    /**
     * Get event count for user
     */
    private function getEventCount(User $user): int
    {
        return \App\Models\PersonEvent::whereHas('person', function ($query) use ($user) {
            $query->where('team_id', $user->current_team_id ?? $user->latestTeam?->id);
        })->count();
    }

    /**
     * Get photo count for user
     */
    private function getPhotoCount(User $user): int
    {
        // Assuming there's a Photo model or similar
        // Adjust this based on your actual photo storage implementation
        return 0; // Placeholder - implement based on your photo system
    }

    /**
     * Check daily activity for a number of days
     */
    private function checkDailyActivity(User $user, int $days): bool
    {
        return $this->getDailyActivityStreak($user) >= $days;
    }

    /**
     * Get daily activity streak
     */
    private function getDailyActivityStreak(User $user): int
    {
        $streak = 0;
        $currentDate = now()->startOfDay();

        for ($i = 0; $i < 365; $i++) { // Check up to a year
            $hasActivity = UserPoint::where('user_id', $user->id)
                ->whereDate('created_at', $currentDate)
                ->exists();

            if ($hasActivity) {
                $streak++;
                $currentDate->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * Get leaderboard data
     */
    public function getLeaderboard(int $limit = 10, string $period = 'all_time'): array
    {
        $query = User::where('show_on_leaderboard', true);

        switch ($period) {
            case 'today':
                $query->withSum(['points as daily_points' => function ($q) {
                    $q->whereDate('created_at', today());
                }], 'points')
                ->orderBy('daily_points', 'desc');
                break;

            case 'week':
                $query->withSum(['points as weekly_points' => function ($q) {
                    $q->where('created_at', '>=', now()->startOfWeek());
                }], 'points')
                ->orderBy('weekly_points', 'desc');
                break;

            case 'month':
                $query->withSum(['points as monthly_points' => function ($q) {
                    $q->where('created_at', '>=', now()->startOfMonth());
                }], 'points')
                ->orderBy('monthly_points', 'desc');
                break;

            default: // all_time
                $query->orderBy('total_points', 'desc');
                break;
        }

        return $query->limit($limit)
            ->get()
            ->map(function ($user, $index) use ($period) {
                return [
                    'rank' => $index + 1,
                    'user' => $user,
                    'points' => $this->getPointsForPeriod($user, $period),
                    'level' => $user->level,
                    'achievements_count' => $user->achievements()->count(),
                ];
            })
            ->toArray();
    }

    /**
     * Get points for a specific period
     */
    private function getPointsForPeriod(User $user, string $period): int
    {
        switch ($period) {
            case 'today':
                return $user->points()->whereDate('created_at', today())->sum('points');
            case 'week':
                return $user->points()->where('created_at', '>=', now()->startOfWeek())->sum('points');
            case 'month':
                return $user->points()->where('created_at', '>=', now()->startOfMonth())->sum('points');
            default:
                return $user->total_points;
        }
    }

    /**
     * Get user statistics
     */
    public function getUserStats(User $user): array
    {
        return [
            'total_points' => $user->total_points,
            'level_info' => $user->getLevelInfo(),
            'achievements_count' => $user->achievements()->count(),
            'recent_achievements' => $user->recentAchievements(7)->get(),
            'recent_points' => $user->recentPoints(7)->get(),
            'daily_points' => $user->getTodaysPoints(),
            'leaderboard_rank' => $user->getLeaderboardRank(),
            'activity_streak' => $this->getDailyActivityStreak($user),
            'progress' => $user->progress()->with('achievement')->incomplete()->get(),
        ];
    }
}