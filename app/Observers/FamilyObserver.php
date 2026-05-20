<?php

namespace App\Observers;

use App\Models\Family;
use App\Services\GamificationService;

class FamilyObserver
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    /**
     * Handle the Family "created" event.
     */
    public function created(Family $family): void
    {
        // Award points for creating a new family relationship
        $user = auth()->user();
        if ($user) {
            $this->gamificationService->awardPoints(
                $user,
                'family_created',
                50,
                "Created a new family relationship",
                ['family_id' => $family->id],
                $family
            );
        }
    }

    /**
     * Handle the Family "updated" event.
     */
    public function updated(Family $family): void
    {
        // Award points for updating family information
        $user = auth()->user();
        if ($user && $family->wasChanged() && !$family->wasRecentlyCreated) {
            $this->gamificationService->awardPoints(
                $user,
                'family_updated',
                15,
                "Updated family relationship information",
                ['family_id' => $family->id],
                $family
            );
        }
    }
}