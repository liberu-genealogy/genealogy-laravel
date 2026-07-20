<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\User;
use App\Models\VirtualEvent;
use App\Services\VideoConferencingService;
use App\Support\Unavailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * A conferencing platform that is not configured must report Unavailable with a
 * reason, not throw a generic exception — so a caller can distinguish "not
 * configured" (a warning the admin can act on) from "configured but failing" (a
 * runtime error), and never records a meeting that was not created.
 */
final class VideoConferencingUnavailableTest extends TestCase
{
    use RefreshDatabase;

    private function event(string $platform): VirtualEvent
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        return VirtualEvent::create([
            'title' => 'Reunion',
            'status' => 'published',
            'start_time' => now()->addWeek(),
            'end_time' => now()->addWeek()->addHours(2),
            'created_by' => $user->id,
            'platform' => $platform,
        ]);
    }

    public function test_creating_a_meeting_on_an_unconfigured_platform_reports_unavailable(): void
    {
        config(['services.zoom.key' => '', 'services.zoom.secret' => '', 'services.zoom.account_id' => '']);

        $event = $this->event('zoom');

        $result = app(VideoConferencingService::class)->createMeeting($event);

        $this->assertInstanceOf(Unavailable::class, $result);
        $this->assertStringContainsString('not configured', $result->reason);
        $this->assertNull($event->fresh()->meeting_id);
    }

    public function test_updating_a_meeting_on_an_unconfigured_platform_reports_unavailable(): void
    {
        config(['services.teams.client_id' => '', 'services.teams.client_secret' => '', 'services.teams.tenant_id' => '']);

        $event = $this->event('teams');

        $result = app(VideoConferencingService::class)->updateMeeting($event);

        $this->assertInstanceOf(Unavailable::class, $result);
        $this->assertStringContainsString('not configured', $result->reason);
    }
}
