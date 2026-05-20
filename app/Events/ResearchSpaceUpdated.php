<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResearchSpaceUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $researchSpaceId;
    public array $payload;

    public function __construct(int $researchSpaceId, array $payload = [])
    {
        $this->researchSpaceId = $researchSpaceId;
        $this->payload = $payload;
    }

    public function broadcastAs(): string
    {
        return 'ResearchSpaceUpdated';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }

    public function broadcastOn()
    {
        return ['research-space.' . $this->researchSpaceId];
    }
}
