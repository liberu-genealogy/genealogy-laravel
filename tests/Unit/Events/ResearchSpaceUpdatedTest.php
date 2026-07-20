<?php

declare(strict_types=1);

namespace Tests\Unit\Events;

use App\Events\ResearchSpaceUpdated;
use Illuminate\Broadcasting\PrivateChannel;
use Tests\TestCase;

/**
 * The event broadcast on a raw string channel ('research-space.{id}'), which
 * Laravel treats as a PUBLIC channel — no authorization, so any client could
 * subscribe and read another space's collaboration. routes/channels.php already
 * restricts this channel to the owner/collaborators; broadcasting on a private
 * channel is what makes that authorization apply.
 */
class ResearchSpaceUpdatedTest extends TestCase
{
    public function test_it_broadcasts_on_a_private_channel(): void
    {
        $channels = (new ResearchSpaceUpdated(42, ['content' => 'x']))->broadcastOn();

        $this->assertCount(1, $channels);
        $this->assertInstanceOf(PrivateChannel::class, $channels[0]);
        $this->assertSame('private-research-space.42', $channels[0]->name);
    }
}
