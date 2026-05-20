<?php

namespace App\Observers;

use App\Models\Person;
use App\Services\GamificationService;

class PersonObserver
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    /**
     * Handle the Person "created" event.
     */
    public function created(Person $person): void
    {
        // Award points for adding a new person
        $user = auth()->user();
        if ($user) {
            $this->gamificationService->awardPoints(
                $user,
                'person_added',
                25,
                "Added {$person->fullname()} to the family tree",
                ['person_id' => $person->id],
                $person
            );
        }
    }

    /**
     * Handle the Person "updated" event.
     */
    public function updated(Person $person): void
    {
        // Award points for updating person information
        $user = auth()->user();
        if ($user && $person->wasChanged() && !$person->wasRecentlyCreated) {
            $this->gamificationService->awardPoints(
                $user,
                'person_updated',
                10,
                "Updated information for {$person->fullname()}",
                ['person_id' => $person->id],
                $person
            );
        }
    }
}