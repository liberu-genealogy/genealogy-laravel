<?php

namespace App\Services\VideoConferencing;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TeamsService implements VideoConferencingInterface
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $tenantId;

    public function __construct()
    {
        $this->baseUrl = 'https://graph.microsoft.com/v1.0';
        $this->clientId = config('services.teams.client_id', '');
        $this->clientSecret = config('services.teams.client_secret', '');
        $this->tenantId = config('services.teams.tenant_id', '');
    }

    public function createMeeting(array $meetingData): array
    {
        $this->validateConfiguration();

        $meeting = [
            'subject' => $meetingData['title'],
            'body' => [
                'contentType' => 'HTML',
                'content' => $meetingData['description'] ?? '',
            ],
            'start' => [
                'dateTime' => Carbon::parse($meetingData['start_time'])->toISOString(),
                'timeZone' => $meetingData['timezone'],
            ],
            'end' => [
                'dateTime' => Carbon::parse($meetingData['end_time'])->toISOString(),
                'timeZone' => $meetingData['timezone'],
            ],
            'isOnlineMeeting' => true,
            'onlineMeetingProvider' => 'teamsForBusiness',
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/me/events', $meeting);

        if (!$response->successful()) {
            throw new Exception('Failed to create Teams meeting: ' . $response->body());
        }

        $createdEvent = $response->json();
        $onlineMeeting = $createdEvent['onlineMeeting'] ?? null;

        if (!$onlineMeeting) {
            throw new Exception('Failed to create Teams online meeting data');
        }

        return [
            'meeting_id' => $createdEvent['id'],
            'password' => null, // Teams uses different authentication
            'meeting_url' => $onlineMeeting['joinUrl'],
            'join_url' => $onlineMeeting['joinUrl'],
            'platform_data' => [
                'event_id' => $createdEvent['id'],
                'conference_id' => $onlineMeeting['conferenceId'] ?? null,
                'organizer_id' => $createdEvent['organizer']['emailAddress']['address'] ?? null,
                'web_link' => $createdEvent['webLink'],
                'created_date_time' => $createdEvent['createdDateTime'],
                'online_meeting_id' => $onlineMeeting['id'] ?? null,
            ],
        ];
    }

    public function updateMeeting(array $meetingData): array
    {
        $this->validateConfiguration();

        $meeting = [
            'subject' => $meetingData['title'],
            'body' => [
                'contentType' => 'HTML',
                'content' => $meetingData['description'] ?? '',
            ],
            'start' => [
                'dateTime' => Carbon::parse($meetingData['start_time'])->toISOString(),
                'timeZone' => $meetingData['timezone'],
            ],
            'end' => [
                'dateTime' => Carbon::parse($meetingData['end_time'])->toISOString(),
                'timeZone' => $meetingData['timezone'],
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ])->patch($this->baseUrl . '/me/events/' . $meetingData['meeting_id'], $meeting);

        if (!$response->successful()) {
            throw new Exception('Failed to update Teams meeting: ' . $response->body());
        }

        return $this->getMeetingDetails($meetingData['meeting_id']) ?? [];
    }

    public function deleteMeeting(string $meetingId): bool
    {
        $this->validateConfiguration();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ])->delete($this->baseUrl . '/me/events/' . $meetingId);

        return $response->successful();
    }

    public function getMeetingDetails(string $meetingId): ?array
    {
        $this->validateConfiguration();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ])->get($this->baseUrl . '/me/events/' . $meetingId);

        if (!$response->successful()) {
            return null;
        }

        $event = $response->json();
        $onlineMeeting = $event['onlineMeeting'] ?? null;

        if (!$onlineMeeting) {
            return null;
        }

        return [
            'meeting_id' => $event['id'],
            'password' => null,
            'meeting_url' => $onlineMeeting['joinUrl'],
            'join_url' => $onlineMeeting['joinUrl'],
            'platform_data' => [
                'event_id' => $event['id'],
                'conference_id' => $onlineMeeting['conferenceId'] ?? null,
                'organizer_id' => $event['organizer']['emailAddress']['address'] ?? null,
                'web_link' => $event['webLink'],
                'start_time' => $event['start']['dateTime'],
                'end_time' => $event['end']['dateTime'],
                'online_meeting_id' => $onlineMeeting['id'] ?? null,
            ],
        ];
    }

    public function getMeetingAttendees(string $meetingId): array
    {
        $this->validateConfiguration();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ])->get($this->baseUrl . '/me/events/' . $meetingId);

        if (!$response->successful()) {
            return [];
        }

        $event = $response->json();
        $attendees = $event['attendees'] ?? [];

        return array_map(function ($attendee) {
            return [
                'name' => $attendee['emailAddress']['name'] ?? $attendee['emailAddress']['address'],
                'email' => $attendee['emailAddress']['address'],
                'response_status' => $attendee['status']['response'] ?? 'none',
                'platform_data' => $attendee,
            ];
        }, $attendees);
    }

    public function sendInvitations(string $meetingId, array $attendeeEmails): bool
    {
        $this->validateConfiguration();

        $attendees = array_map(function ($email) {
            return [
                'emailAddress' => [
                    'address' => $email,
                    'name' => $email,
                ],
                'type' => 'required',
            ];
        }, $attendeeEmails);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ])->get($this->baseUrl . '/me/events/' . $meetingId);

        if (!$response->successful()) {
            return false;
        }

        $event = $response->json();
        $event['attendees'] = array_merge($event['attendees'] ?? [], $attendees);

        $updateResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ])->patch($this->baseUrl . '/me/events/' . $meetingId, [
            'attendees' => $event['attendees'],
        ]);

        return $updateResponse->successful();
    }

    public function isConfigured(): bool
    {
        return !empty($this->clientId) && 
               !empty($this->clientSecret) && 
               !empty($this->tenantId);
    }

    protected function validateConfiguration(): void
    {
        if (!$this->isConfigured()) {
            throw new Exception('Teams service is not properly configured. Please check your API credentials.');
        }
    }

    protected function getAccessToken(): string
    {
        $response = Http::asForm()->post("https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token", [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => 'https://graph.microsoft.com/.default',
            'grant_type' => 'client_credentials',
        ]);

        if (!$response->successful()) {
            throw new Exception('Failed to get Teams access token: ' . $response->body());
        }

        $data = $response->json();
        return $data['access_token'];
    }
}