<?php

namespace App\Services\VideoConferencing;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GoogleMeetService implements VideoConferencingInterface
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;

    public function __construct()
    {
        $this->baseUrl = 'https://www.googleapis.com/calendar/v3';
        $this->clientId = config('services.google.client_id', '');
        $this->clientSecret = config('services.google.client_secret', '');
        $this->refreshToken = config('services.google.refresh_token', '');
    }

    public function createMeeting(array $meetingData): array
    {
        $this->validateConfiguration();

        $event = [
            'summary' => $meetingData['title'],
            'description' => $meetingData['description'] ?? '',
            'start' => [
                'dateTime' => Carbon::parse($meetingData['start_time'])->toISOString(),
                'timeZone' => $meetingData['timezone'],
            ],
            'end' => [
                'dateTime' => Carbon::parse($meetingData['end_time'])->toISOString(),
                'timeZone' => $meetingData['timezone'],
            ],
            'conferenceData' => [
                'createRequest' => [
                    'requestId' => uniqid(),
                    'conferenceSolutionKey' => [
                        'type' => 'hangoutsMeet'
                    ],
                ],
            ],
            'attendees' => [],
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'email', 'minutes' => 24 * 60],
                    ['method' => 'popup', 'minutes' => 10],
                ],
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/calendars/primary/events?conferenceDataVersion=1', $event);

        if (!$response->successful()) {
            throw new Exception('Failed to create Google Meet: ' . $response->body());
        }

        $createdEvent = $response->json();
        $meetData = $createdEvent['conferenceData']['entryPoints'][0] ?? null;

        if (!$meetData) {
            throw new Exception('Failed to create Google Meet conference data');
        }

        return [
            'meeting_id' => $createdEvent['id'],
            'password' => null, // Google Meet doesn't use passwords
            'meeting_url' => $meetData['uri'],
            'join_url' => $meetData['uri'],
            'platform_data' => [
                'event_id' => $createdEvent['id'],
                'conference_id' => $createdEvent['conferenceData']['conferenceId'],
                'html_link' => $createdEvent['htmlLink'],
                'created' => $createdEvent['created'],
                'status' => $createdEvent['status'],
            ],
        ];
    }

    public function updateMeeting(array $meetingData): array
    {
        $this->validateConfiguration();

        $event = [
            'summary' => $meetingData['title'],
            'description' => $meetingData['description'] ?? '',
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
        ])->put($this->baseUrl . '/calendars/primary/events/' . $meetingData['meeting_id'], $event);

        if (!$response->successful()) {
            throw new Exception('Failed to update Google Meet: ' . $response->body());
        }

        return $this->getMeetingDetails($meetingData['meeting_id']) ?? [];
    }

    public function deleteMeeting(string $meetingId): bool
    {
        $this->validateConfiguration();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ])->delete($this->baseUrl . '/calendars/primary/events/' . $meetingId);

        return $response->successful();
    }

    public function getMeetingDetails(string $meetingId): ?array
    {
        $this->validateConfiguration();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ])->get($this->baseUrl . '/calendars/primary/events/' . $meetingId);

        if (!$response->successful()) {
            return null;
        }

        $event = $response->json();
        $meetData = $event['conferenceData']['entryPoints'][0] ?? null;

        if (!$meetData) {
            return null;
        }

        return [
            'meeting_id' => $event['id'],
            'password' => null,
            'meeting_url' => $meetData['uri'],
            'join_url' => $meetData['uri'],
            'platform_data' => [
                'event_id' => $event['id'],
                'conference_id' => $event['conferenceData']['conferenceId'] ?? null,
                'html_link' => $event['htmlLink'],
                'status' => $event['status'],
                'start_time' => $event['start']['dateTime'],
                'end_time' => $event['end']['dateTime'],
            ],
        ];
    }

    public function getMeetingAttendees(string $meetingId): array
    {
        $this->validateConfiguration();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ])->get($this->baseUrl . '/calendars/primary/events/' . $meetingId);

        if (!$response->successful()) {
            return [];
        }

        $event = $response->json();
        $attendees = $event['attendees'] ?? [];

        return array_map(function ($attendee) {
            return [
                'name' => $attendee['displayName'] ?? $attendee['email'],
                'email' => $attendee['email'],
                'response_status' => $attendee['responseStatus'] ?? 'needsAction',
                'platform_data' => $attendee,
            ];
        }, $attendees);
    }

    public function sendInvitations(string $meetingId, array $attendeeEmails): bool
    {
        $this->validateConfiguration();

        $attendees = array_map(function ($email) {
            return ['email' => $email];
        }, $attendeeEmails);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ])->get($this->baseUrl . '/calendars/primary/events/' . $meetingId);

        if (!$response->successful()) {
            return false;
        }

        $event = $response->json();
        $event['attendees'] = array_merge($event['attendees'] ?? [], $attendees);

        $updateResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ])->put($this->baseUrl . '/calendars/primary/events/' . $meetingId . '?sendUpdates=all', $event);

        return $updateResponse->successful();
    }

    public function isConfigured(): bool
    {
        return !empty($this->clientId) && 
               !empty($this->clientSecret) && 
               !empty($this->refreshToken);
    }

    protected function validateConfiguration(): void
    {
        if (!$this->isConfigured()) {
            throw new Exception('Google Meet service is not properly configured. Please check your API credentials.');
        }
    }

    protected function getAccessToken(): string
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $this->refreshToken,
            'grant_type' => 'refresh_token',
        ]);

        if (!$response->successful()) {
            throw new Exception('Failed to get Google access token: ' . $response->body());
        }

        $data = $response->json();
        return $data['access_token'];
    }
}