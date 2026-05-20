<?php

namespace App\Services;

use App\Models\VirtualEvent;
use App\Services\VideoConferencing\ZoomService;
use App\Services\VideoConferencing\GoogleMeetService;
use App\Services\VideoConferencing\TeamsService;
use App\Services\VideoConferencing\VideoConferencingInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class VideoConferencingService
{
    protected array $services = [];

    public function __construct()
    {
        $this->services = [
            'zoom' => new ZoomService(),
            'google_meet' => new GoogleMeetService(),
            'teams' => new TeamsService(),
        ];
    }

    /**
     * Create a meeting for the given virtual event
     */
    public function createMeeting(VirtualEvent $event): array
    {
        try {
            $service = $this->getService($event->platform);

            $meetingData = [
                'title' => $event->title,
                'description' => $event->description,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
                'timezone' => $event->timezone,
                'host_email' => $event->host_email ?? $event->creator->email,
                'max_attendees' => $event->max_attendees,
                'require_password' => true,
            ];

            $result = $service->createMeeting($meetingData);

            // Update the event with meeting details
            $event->update([
                'meeting_id' => $result['meeting_id'],
                'meeting_password' => $result['password'] ?? null,
                'meeting_url' => $result['meeting_url'],
                'join_url' => $result['join_url'],
                'platform_data' => $result['platform_data'] ?? [],
            ]);

            Log::info('Meeting created successfully', [
                'event_id' => $event->id,
                'platform' => $event->platform,
                'meeting_id' => $result['meeting_id'],
            ]);

            return $result;

        } catch (Exception $e) {
            Log::error('Failed to create meeting', [
                'event_id' => $event->id,
                'platform' => $event->platform,
                'error' => $e->getMessage(),
            ]);

            throw new Exception("Failed to create {$event->platform} meeting: " . $e->getMessage());
        }
    }

    /**
     * Update an existing meeting
     */
    public function updateMeeting(VirtualEvent $event): array
    {
        try {
            $service = $this->getService($event->platform);

            $meetingData = [
                'meeting_id' => $event->meeting_id,
                'title' => $event->title,
                'description' => $event->description,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
                'timezone' => $event->timezone,
                'max_attendees' => $event->max_attendees,
            ];

            $result = $service->updateMeeting($meetingData);

            // Update the event with new meeting details
            $event->update([
                'meeting_url' => $result['meeting_url'] ?? $event->meeting_url,
                'join_url' => $result['join_url'] ?? $event->join_url,
                'platform_data' => array_merge($event->platform_data ?? [], $result['platform_data'] ?? []),
            ]);

            Log::info('Meeting updated successfully', [
                'event_id' => $event->id,
                'platform' => $event->platform,
                'meeting_id' => $event->meeting_id,
            ]);

            return $result;

        } catch (Exception $e) {
            Log::error('Failed to update meeting', [
                'event_id' => $event->id,
                'platform' => $event->platform,
                'meeting_id' => $event->meeting_id,
                'error' => $e->getMessage(),
            ]);

            throw new Exception("Failed to update {$event->platform} meeting: " . $e->getMessage());
        }
    }

    /**
     * Delete a meeting
     */
    public function deleteMeeting(VirtualEvent $event): bool
    {
        try {
            $service = $this->getService($event->platform);

            $result = $service->deleteMeeting($event->meeting_id);

            // Clear meeting data from event
            $event->update([
                'meeting_id' => null,
                'meeting_password' => null,
                'meeting_url' => null,
                'join_url' => null,
                'platform_data' => null,
            ]);

            Log::info('Meeting deleted successfully', [
                'event_id' => $event->id,
                'platform' => $event->platform,
                'meeting_id' => $event->meeting_id,
            ]);

            return $result;

        } catch (Exception $e) {
            Log::error('Failed to delete meeting', [
                'event_id' => $event->id,
                'platform' => $event->platform,
                'meeting_id' => $event->meeting_id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get meeting details
     */
    public function getMeetingDetails(VirtualEvent $event): ?array
    {
        try {
            $service = $this->getService($event->platform);
            return $service->getMeetingDetails($event->meeting_id);

        } catch (Exception $e) {
            Log::error('Failed to get meeting details', [
                'event_id' => $event->id,
                'platform' => $event->platform,
                'meeting_id' => $event->meeting_id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Get meeting attendees/participants
     */
    public function getMeetingAttendees(VirtualEvent $event): array
    {
        try {
            $service = $this->getService($event->platform);
            return $service->getMeetingAttendees($event->meeting_id);

        } catch (Exception $e) {
            Log::error('Failed to get meeting attendees', [
                'event_id' => $event->id,
                'platform' => $event->platform,
                'meeting_id' => $event->meeting_id,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Send invitations to attendees
     */
    public function sendInvitations(VirtualEvent $event, array $attendeeEmails = []): bool
    {
        try {
            $service = $this->getService($event->platform);

            if (empty($attendeeEmails)) {
                $attendeeEmails = $event->attendees()
                    ->whereNotNull('user_id')
                    ->with('user')
                    ->get()
                    ->pluck('user.email')
                    ->filter()
                    ->toArray();
            }

            return $service->sendInvitations($event->meeting_id, $attendeeEmails);

        } catch (Exception $e) {
            Log::error('Failed to send invitations', [
                'event_id' => $event->id,
                'platform' => $event->platform,
                'meeting_id' => $event->meeting_id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Generate a custom meeting URL for platforms that support it
     */
    public function generateCustomMeetingUrl(VirtualEvent $event): string
    {
        if ($event->platform === 'custom') {
            return route('virtual-events.join', [
                'event' => $event->id,
                'token' => $event->platform_data['custom_token'] ?? 'default'
            ]);
        }

        return $event->join_url ?? $event->meeting_url ?? '';
    }

    /**
     * Get the appropriate service for the platform
     */
    protected function getService(string $platform): VideoConferencingInterface
    {
        if (!isset($this->services[$platform])) {
            throw new Exception("Unsupported video conferencing platform: {$platform}");
        }

        return $this->services[$platform];
    }

    /**
     * Get available platforms
     */
    public function getAvailablePlatforms(): array
    {
        return [
            'zoom' => [
                'name' => 'Zoom',
                'enabled' => config('services.zoom.enabled', false),
                'requires_api' => true,
            ],
            'google_meet' => [
                'name' => 'Google Meet',
                'enabled' => config('services.google.enabled', false),
                'requires_api' => true,
            ],
            'teams' => [
                'name' => 'Microsoft Teams',
                'enabled' => config('services.teams.enabled', false),
                'requires_api' => true,
            ],
            'custom' => [
                'name' => 'Custom/Other',
                'enabled' => true,
                'requires_api' => false,
            ],
        ];
    }

    /**
     * Check if a platform is properly configured
     */
    public function isPlatformConfigured(string $platform): bool
    {
        try {
            $service = $this->getService($platform);
            return $service->isConfigured();
        } catch (Exception $e) {
            return false;
        }
    }
}