<?php

namespace App\Services\VideoConferencing;

interface VideoConferencingInterface
{
    /**
     * Create a new meeting
     */
    public function createMeeting(array $meetingData): array;

    /**
     * Update an existing meeting
     */
    public function updateMeeting(array $meetingData): array;

    /**
     * Delete a meeting
     */
    public function deleteMeeting(string $meetingId): bool;

    /**
     * Get meeting details
     */
    public function getMeetingDetails(string $meetingId): ?array;

    /**
     * Get meeting attendees/participants
     */
    public function getMeetingAttendees(string $meetingId): array;

    /**
     * Send invitations to attendees
     */
    public function sendInvitations(string $meetingId, array $attendeeEmails): bool;

    /**
     * Check if the service is properly configured
     */
    public function isConfigured(): bool;
}