<?php

namespace App\Listeners;

use App\Events\UserLeveledUp;
use App\Services\GamificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UserLeveledUpListener implements ShouldQueue
{
    use InteractsWithQueue;

    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(UserLeveledUp $event): void
    {
        // Log the level up
        Log::info('User leveled up', [
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'old_level' => $event->oldLevel,
            'new_level' => $event->newLevel,
            'total_points' => $event->user->total_points,
        ]);

        // Award bonus points for leveling up
        $bonusPoints = $event->newLevel * 10; // 10 points per level
        $this->gamificationService->awardPoints(
            $event->user,
            'level_up_bonus',
            $bonusPoints,
            "Level up bonus for reaching Level {$event->newLevel}",
            [
                'old_level' => $event->oldLevel,
                'new_level' => $event->newLevel,
            ]
        );

        // Check for level-based achievements
        $this->gamificationService->checkAchievements($event->user, 'level_up', [
            'old_level' => $event->oldLevel,
            'new_level' => $event->newLevel,
        ]);

        // You could also:
        // - Send congratulatory email
        // - Unlock new features based on level
        // - Award special badges or titles
        // - Update user permissions
    }
}