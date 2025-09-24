<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLeveledUp implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public int $oldLevel;
    public int $newLevel;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, int $oldLevel, int $newLevel)
    {
        $this->user = $user;
        $this->oldLevel = $oldLevel;
        $this->newLevel = $newLevel;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
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
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'total_points' => $this->user->total_points,
                'old_level' => $this->oldLevel,
                'new_level' => $this->newLevel,
            ],
            'message' => "🎊 Level Up! You've reached Level {$this->newLevel}!",
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'user.leveled_up';
    }
}