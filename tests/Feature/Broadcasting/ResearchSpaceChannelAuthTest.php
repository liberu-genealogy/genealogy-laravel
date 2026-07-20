<?php

declare(strict_types=1);

namespace Tests\Feature\Broadcasting;

use App\Models\ResearchSpace;
use App\Models\ResearchSpaceCollaborator;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * ResearchSpaceUpdated broadcasts on a private channel (#1587), but channels.php
 * was never loaded (no withBroadcasting), so the owner/collaborator
 * authorization never ran and /broadcasting/auth didn't exist. With broadcasting
 * wired, the endpoint enforces the channel: owner and collaborators authorize,
 * everyone else is denied.
 */
class ResearchSpaceChannelAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // The null/log drivers no-op channel auth; use the pusher-protocol driver
        // (what Reverb speaks) so the authorization callback actually executes.
        // Dummy credentials are enough — socket auth is a local HMAC, no network.
        config([
            'broadcasting.default' => 'pusher',
            'broadcasting.connections.pusher.key' => 'test-key',
            'broadcasting.connections.pusher.secret' => 'test-secret',
            'broadcasting.connections.pusher.app_id' => 'test-app',
        ]);

        // channels.php was loaded at boot against the default (null) driver, so
        // re-register it on the pusher broadcaster we just selected. In production
        // the driver is fixed via env before boot, so this only matters in-test.
        require base_path('routes/channels.php');
    }

    private function authorize(User $user, int $spaceId): TestResponse
    {
        return $this->actingAs($user)->postJson('/broadcasting/auth', [
            'channel_name' => 'private-research-space.'.$spaceId,
            'socket_id' => '1234.5678',
        ]);
    }

    private function spaceOwnedBy(User $owner): ResearchSpace
    {
        return ResearchSpace::create([
            'name' => 'Space',
            'slug' => 'space-'.uniqid(),
            'owner_id' => $owner->id,
        ]);
    }

    public function test_the_owner_is_authorized_on_their_space_channel(): void
    {
        $owner = User::factory()->create();
        $space = $this->spaceOwnedBy($owner);

        $this->authorize($owner, $space->id)->assertOk();
    }

    public function test_a_collaborator_is_authorized(): void
    {
        $space = $this->spaceOwnedBy(User::factory()->create());
        $collaborator = User::factory()->create();
        ResearchSpaceCollaborator::create([
            'research_space_id' => $space->id,
            'user_id' => $collaborator->id,
            'role' => 'editor',
        ]);

        $this->authorize($collaborator, $space->id)->assertOk();
    }

    public function test_a_stranger_is_denied(): void
    {
        $space = $this->spaceOwnedBy(User::factory()->create());
        $stranger = User::factory()->create();

        $this->authorize($stranger, $space->id)->assertForbidden();
    }
}
