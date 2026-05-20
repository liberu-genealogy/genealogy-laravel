<?php

namespace App\Notifications;

use App\Models\Achievement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AchievementUnlockedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Achievement $achievement;

    /**
     * Create a new notification instance.
     */
    public function __construct(Achievement $achievement)
    {
        $this->achievement = $achievement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🎉 Achievement Unlocked: ' . $this->achievement->name)
            ->greeting('Congratulations!')
            ->line("You've unlocked a new achievement: **{$this->achievement->name}**")
            ->line($this->achievement->description)
            ->line("You earned {$this->achievement->points} points for this achievement!")
            ->action('View Your Achievements', url('/gamification'))
            ->line('Keep up the great work on your genealogy research!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'achievement_unlocked',
            'achievement_id' => $this->achievement->id,
            'achievement_key' => $this->achievement->key,
            'achievement_name' => $this->achievement->name,
            'achievement_description' => $this->achievement->description,
            'achievement_icon' => $this->achievement->icon,
            'points_awarded' => $this->achievement->points,
            'message' => "🎉 Achievement Unlocked: {$this->achievement->name}!",
        ];
    }
}