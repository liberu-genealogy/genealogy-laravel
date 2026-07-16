<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Person;
use App\Models\Team;
use App\Notifications\DuplicatePersonDetectedNotification;
use App\Services\DuplicateDetectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScanForDuplicatePersons implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public float $threshold = 0.7, public int $limitPerPerson = 10)
    {
    }

    public function handle(DuplicateDetectionService $detector): void
    {
        // scan and persist DuplicateMatch records
        $created = $detector->scan($this->threshold, $this->limitPerPerson);

        // Only notify on brand-new pairs; a re-scan re-returns already-persisted rows.
        $newMatches = $created->filter(fn ($m) => $m->wasRecentlyCreated);
        if ($newMatches->isEmpty()) {
            return;
        }

        // Resolve the owning team via the canonical (primary) person. This scan runs
        // unauthenticated (scheduled), so DuplicateMatch.team_id is not populated —
        // bulk-load the person team_ids to avoid an N+1 lookup.
        // ponytail: bounded by limitPerPerson * people per run.
        $teamByPerson = Person::whereIn('id', $newMatches->pluck('primary_person_id')->unique())
            ->pluck('team_id', 'id');

        $countByTeam = [];
        $unowned = 0;
        foreach ($newMatches as $m) {
            $teamId = $teamByPerson->get($m->primary_person_id);
            if ($teamId === null) {
                $unowned++;
                continue;
            }
            $countByTeam[$teamId] = ($countByTeam[$teamId] ?? 0) + 1;
        }

        foreach ($countByTeam as $teamId => $count) {
            $owner = Team::find($teamId)?->owner;
            if ($owner) {
                $owner->notify(new DuplicatePersonDetectedNotification($count));
            } else {
                Log::info('Duplicate scan: team has no owner to notify', ['team_id' => $teamId, 'new_matches' => $count]);
            }
        }

        if ($unowned > 0) {
            Log::info('Duplicate scan: new matches with no owning team; notification skipped', ['count' => $unowned]);
        }
    }
}
