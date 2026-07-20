<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResearchSpaceUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $researchSpaceId, public array $payload = []) {}

    public function broadcastAs(): string
    {
        return 'ResearchSpaceUpdated';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }

    /**
     * @return array<int, PrivateChannel>
     */
    public function broadcastOn(): array
    {
        // Private channel so routes/channels.php authorises subscribers to the
        // space's owner/collaborators. A raw string is a public channel — no
        // auth — which would expose one space's collaboration to any listener.
        return [new PrivateChannel('research-space.'.$this->researchSpaceId)];
    }
}
