<?php

namespace App\Services\VideoConferencing;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ZoomService implements VideoConferencingInterface
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $apiSecret;
    protected string $accountId;

    public function __construct()
    {
        $this->baseUrl = config('services.zoom.base_url', 'https://api.zoom.us/v2');
        $this->apiKey = config('services.zoom.key', '');
        $this->apiSecret = config('services.zoom.secret', '');
        $this->accountId = config('services.zoom.account_id', '');
    }

    public function createMeeting(array $meetingData): array
    {
        $this->validateConfiguration();

        $payload = [
            'topic' => $meetingData['title'],
            'type' => 2, // Scheduled meeting
            'start_time' => Carbon::parse($meetingData['start_time'])->toISOString(),
            'duration' => Carbon::parse($meetingData['start_time'])->diffInMinutes($meetingData['end_time']),
            'timezone' => $meetingData['timezone'],
            'agenda' => $meetingData['description'] ?? '',
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'join_before_host' => false,
                'mute_upon_entry' => true,
                'watermark' => false,
                'use_pmi' => false,
                'approval_type' => 0, // Automatically approve
                'audio' => 'both',
                'auto_recording' => 'none',
                'waiting_room' => true,
            ],
        ];

        if (isset($meetingData['max_attendees'])) {
            $payload['settings']['meeting_capacity'] = $meetingData['max_attendees'];
        }

        if ($meetingData['require_password'] ?? true) {
            $payload['password'] = $this->generatePassword();
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/users/me/meetings', $payload);

        if (!$response->successful()) {
            throw new Exception('Failed to create Zoom meeting: ' . $response->body());
        }

        $meeting = $response->json();

        return [
            'meeting_id' => (string) $meeting['id'],
            'password' => $meeting['password'] ?? null,
            'meeting_url' => $meeting['start_url'],
            'join_url' => $meeting['join_url'],
            'platform_data' => [
                'uuid' => $meeting['uuid'],
                'host_id' => $meeting['host_id'],
                'topic' => $meeting['topic'],
                'status' => $meeting['status'],
                'created_at' => $meeting['created_at'],
            ],
        ];
    }

    public function updateMeeting(array $meetingData): array
    {
        $this->validateConfiguration();

        $payload = [
            'topic' => $meetingData['title'],
            'start_time' => Carbon::parse($meetingData['start_time'])->toISOString(),
            'duration' => Carbon::parse($meetingData['start_time'])->diffInMinutes($meetingData['end_time']),
            'timezone' => $meetingData['timezone'],
            'agenda' => $meetingData['description'] ?? '',
        ];

        if (isset($meetingData['max_attendees'])) {
            $payload['settings']['meeting_capacity'] = $meetingData['max_attendees'];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ])->patch($this->baseUrl . '/meetings/' . $meetingData['meeting_id'], $payload);

        if (!$response->successful()) {
            throw new Exception('Failed to update Zoom meeting: ' . $response->body());
        }

        // Get updated meeting details
        return $this->getMeetingDetails($meetingData['meeting_id']) ?? [];
    }

    public function deleteMeeting(string $meetingId): bool
    {
        $this->validateConfiguration();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ])->delete($this->baseUrl . '/meetings/' . $meetingId);

        return $response->successful();
    }

    public function getMeetingDetails(string $meetingId): ?array
    {
        $this->validateConfiguration();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ])->get($this->baseUrl . '/meetings/' . $meetingId);

        if (!$response->successful()) {
            return null;
        }

        $meeting = $response->json();

        return [
            'meeting_id' => (string) $meeting['id'],
            'password' => $meeting['password'] ?? null,
            'meeting_url' => $meeting['start_url'],
            'join_url' => $meeting['join_url'],
            'platform_data' => [
                'uuid' => $meeting['uuid'],
                'host_id' => $meeting['host_id'],
                'topic' => $meeting['topic'],
                'status' => $meeting['status'],
                'start_time' => $meeting['start_time'],
                'duration' => $meeting['duration'],
            ],
        ];
    }

    public function getMeetingAttendees(string $meetingId): array
    {
        $this->validateConfiguration();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ])->get($this->baseUrl . '/meetings/' . $meetingId . '/participants');

        if (!$response->successful()) {
            return [];
        }

        $data = $response->json();
        $participants = $data['participants'] ?? [];

        return array_map(function ($participant) {
            return [
                'name' => $participant['name'],
                'email' => $participant['user_email'] ?? '',
                'joined_at' => $participant['join_time'] ?? null,
                'left_at' => $participant['leave_time'] ?? null,
                'duration' => $participant['duration'] ?? 0,
                'platform_data' => $participant,
            ];
        }, $participants);
    }

    public function sendInvitations(string $meetingId, array $attendeeEmails): bool
    {
        // Zoom doesn't have a direct API for sending invitations
        // This would typically be handled by the application's email system
        // using the meeting details
        return true;
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && 
               !empty($this->apiSecret) && 
               !empty($this->accountId);
    }

    protected function validateConfiguration(): void
    {
        if (!$this->isConfigured()) {
            throw new Exception('Zoom service is not properly configured. Please check your API credentials.');
        }
    }

    protected function getAccessToken(): string
    {
        // In a real implementation, you would use OAuth 2.0 or JWT
        // This is a simplified version for demonstration
        $response = Http::asForm()->post('https://zoom.us/oauth/token', [
            'grant_type' => 'account_credentials',
            'account_id' => $this->accountId,
        ], [
            'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->apiSecret),
        ]);

        if (!$response->successful()) {
            throw new Exception('Failed to get Zoom access token: ' . $response->body());
        }

        $data = $response->json();
        return $data['access_token'];
    }

    protected function generatePassword(): string
    {
        return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
    }
}