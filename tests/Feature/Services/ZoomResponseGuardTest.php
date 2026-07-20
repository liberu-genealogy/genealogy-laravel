<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Services\VideoConferencing\ZoomService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Zoom's responses omit fields legitimately — a recurring meeting has no fixed
 * start_time, a freshly created meeting may not echo every platform field. The
 * service must read them defensively: an unguarded read throws, and createMeeting
 * throws *after* the meeting exists at Zoom, orphaning it. Guards the reads in
 * createMeeting / getMeetingDetails, mirroring getMeetingAttendees two methods down.
 */
final class ZoomResponseGuardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.zoom.key' => 'k',
            'services.zoom.secret' => 's',
            'services.zoom.account_id' => 'a',
            'services.zoom.base_url' => 'https://api.zoom.us/v2',
        ]);
    }

    private function fakeZoom(array $meetingResponse): void
    {
        Http::fake([
            'zoom.us/oauth/token' => Http::response(['access_token' => 'tok'], 200),
            'api.zoom.us/v2/*' => Http::response($meetingResponse, 200),
        ]);
    }

    public function test_a_recurring_meeting_without_a_start_time_can_be_retrieved(): void
    {
        // A recurring meeting with no fixed time: Zoom omits start_time and duration.
        $this->fakeZoom([
            'id' => 123,
            'start_url' => 'https://zoom.us/s/123',
            'join_url' => 'https://zoom.us/j/123',
            'uuid' => 'abc==',
            'host_id' => 'h1',
            'topic' => 'Recurring research call',
            'status' => 'waiting',
        ]);

        $result = (new ZoomService)->getMeetingDetails('123');

        $this->assertSame('123', $result['meeting_id']);
        $this->assertNull($result['platform_data']['start_time']);
    }

    public function test_a_create_response_missing_optional_fields_does_not_throw(): void
    {
        // Only the essentials come back; the platform_data fields are absent.
        $this->fakeZoom([
            'id' => 456,
            'start_url' => 'https://zoom.us/s/456',
            'join_url' => 'https://zoom.us/j/456',
        ]);

        $result = (new ZoomService)->createMeeting([
            'title' => 'Family sync',
            'start_time' => '2026-08-01T10:00:00Z',
            'end_time' => '2026-08-01T11:00:00Z',
            'timezone' => 'UTC',
        ]);

        $this->assertSame('456', $result['meeting_id']);
        $this->assertNull($result['platform_data']['uuid']);
    }

    public function test_the_update_path_survives_a_recurring_meeting(): void
    {
        $this->fakeZoom([
            'id' => 789,
            'start_url' => 'https://zoom.us/s/789',
            'join_url' => 'https://zoom.us/j/789',
        ]);

        $result = (new ZoomService)->updateMeeting([
            'meeting_id' => '789',
            'title' => 'Renamed',
            'start_time' => '2026-08-01T10:00:00Z',
            'end_time' => '2026-08-01T11:00:00Z',
            'timezone' => 'UTC',
        ]);

        $this->assertSame('789', $result['meeting_id']);
    }
}
