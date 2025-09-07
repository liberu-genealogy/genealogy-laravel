<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AchievementUnlockedNotification;

class AchievementUnlockedListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(AchievementUnlocked $event): void
    {
        // Log the achievement unlock
        Log::info('Achievement unlocked', [
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'achievement_key' => $event->achievement->key,
            'achievement_name' => $event->achievement->name,
            'points_awarded' => $event->achievement->points,
        ]);

        // Send notification to user (if notification class exists)
        try {
            $event->user->notify(new AchievementUnlockedNotification($event->achievement));
        } catch (\Exception $e) {
            // Notification class might not exist yet, log the error
            Log::warning('Could not send achievement notification', [
                'error' => $e->getMessage(),
                'user_id' => $event->user->id,
                'achievement_id' => $event->achievement->id,
            ]);
        }

        // You could also:
        // - Send email notifications
        // - Update external analytics
        // - Trigger social media sharing
        // - Award bonus points for special achievements
        // - Check for meta-achievements (like "unlock 5 achievements")
    }
}