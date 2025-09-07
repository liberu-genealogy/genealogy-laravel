<?php

namespace App\Events;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AchievementUnlocked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public Achievement $achievement;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Achievement $achievement)
    {
        $this->user = $user;
        $this->achievement = $achievement;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->user->id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'achievement' => [
                'id' => $this->achievement->id,
                'key' => $this->achievement->key,
                'name' => $this->achievement->name,
                'description' => $this->achievement->description,
                'icon' => $this->achievement->icon,
                'points' => $this->achievement->points,
                'badge_color' => $this->achievement->badge_color,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'total_points' => $this->user->total_points,
                'level' => $this->user->level,
            ],
            'message' => "🎉 Achievement Unlocked: {$this->achievement->name}!",
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'achievement.unlocked';
    }
}