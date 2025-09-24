<?php

namespace App\Observers;

use App\Models\PersonEvent;
use App\Services\GamificationService;

class PersonEventObserver
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    /**
     * Handle the PersonEvent "created" event.
     */
    public function created(PersonEvent $event): void
    {
        // Award points for adding a life event
        $user = auth()->user();
        if ($user) {
            $points = $this->getPointsForEventType($event->title);

            $this->gamificationService->awardPoints(
                $user,
                'event_added',
                $points,
                "Added {$event->title} event for {$event->person->fullname()}",
                [
                    'event_id' => $event->id,
                    'event_type' => $event->title,
                    'person_id' => $event->person_id
                ],
                $event
            );
        }
    }

    /**
     * Handle the PersonEvent "updated" event.
     */
    public function updated(PersonEvent $event): void
    {
        // Award points for updating event information
        $user = auth()->user();
        if ($user && $event->wasChanged() && !$event->wasRecentlyCreated) {
            $this->gamificationService->awardPoints(
                $user,
                'event_updated',
                5,
                "Updated {$event->title} event for {$event->person->fullname()}",
                [
                    'event_id' => $event->id,
                    'event_type' => $event->title,
                    'person_id' => $event->person_id
                ],
                $event
            );
        }
    }

    /**
     * Get points based on event type
     */
    private function getPointsForEventType(string $eventType): int
    {
        return match(strtoupper($eventType)) {
            'BIRT' => 30, // Birth events are important
            'DEAT' => 30, // Death events are important
            'MARR' => 25, // Marriage events
            'BURI' => 20, // Burial events
            'BAPM', 'CHR' => 15, // Baptism/Christening
            'GRAD' => 15, // Graduation
            'OCCU' => 10, // Occupation
            'RESI' => 10, // Residence
            default => 15, // Default for other events
        };
    }
}