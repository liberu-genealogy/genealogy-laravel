<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResearchSpaceUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $researchSpaceId, public array $payload = [])
    {
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
