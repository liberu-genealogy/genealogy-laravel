<?php

namespace App\Jobs;

use App\Models\Person;
use App\Models\Team;
use App\Notifications\RecordSuggestionNotification;
use App\Services\RecordMatcher\Providers\AncestryProvider;
use App\Services\RecordMatcher\Providers\ExampleProvider;
use App\Services\RecordMatcher\Providers\FamilySearchProvider;
use App\Services\RecordMatcher\Providers\MyHeritageProvider;
use App\Services\RecordMatcher\RecordMatcherService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use ReflectionClass;

class RunRecordMatchingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1200;

    public function handle(RecordMatcherService $matcher): void
    {
        // Initialize providers based on configuration
        $providers = [];

        // Add MyHeritage provider if configured
        $myHeritage = new MyHeritageProvider;
        if ($myHeritage->isConfigured()) {
            $providers[] = $myHeritage;
        }

        // Add Ancestry provider if configured
        $ancestry = new AncestryProvider;
        if ($ancestry->isConfigured()) {
            $providers[] = $ancestry;
        }

        // Add FamilySearch provider if configured
        $familySearch = new FamilySearchProvider;
        if ($familySearch->isConfigured()) {
            $providers[] = $familySearch;
        }

        // If no providers configured, use example provider for testing
        if ($providers === []) {
            Log::warning('No genealogy providers configured, using example provider');
            $providers[] = new ExampleProvider;
        }

        Log::info('Record matching job started', [
            'providers' => array_map(fn ($p): string => new ReflectionClass($p)->getShortName(), $providers),
        ]);

        // Fetch a sample of persons to run against (could be queued per-person).
        // Filter on surn — the GEDCOM surname column import populates; last_name
        // is never written, so filtering on it selected nobody.
        $persons = Person::whereNotNull('surn')->limit(200)->get();

        $totalMatches = 0;
        $newByTeam = [];
        $unowned = 0;

        foreach ($persons as $person) {
            foreach ($providers as $provider) {
                try {
                    $candidates = $provider->search($person);
                    $scored = $matcher->scoreCandidates($person, $candidates);

                    foreach ($scored as $entry) {
                        $candidate = $entry['candidate'];
                        $score = $entry['score'];
                        // Only persist suggestions above a threshold (e.g., 0.45)
                        if ($score >= config('ai_record_match.min_confidence', 0.45)) {
                            $suggestion = $matcher->persistSuggestion(
                                $person->id,
                                new ReflectionClass($provider)->getShortName(),
                                $candidate,
                                $score,
                                $person->team_id,
                            );
                            $totalMatches++;

                            // Group brand-new suggestions by the person's owning team so
                            // we send one summary per owner instead of one email per record.
                            if ($suggestion->wasRecentlyCreated) {
                                if ($person->team_id) {
                                    $newByTeam[$person->team_id] = ($newByTeam[$person->team_id] ?? 0) + 1;
                                } else {
                                    $unowned++;
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Record matching failed for person', [
                        'person_id' => $person->id,
                        'provider' => new ReflectionClass($provider)->getShortName(),
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        // Notify each affected team's owner once, summarising their new suggestions.
        foreach ($newByTeam as $teamId => $count) {
            $owner = Team::find($teamId)?->owner;
            if ($owner) {
                $owner->notify(new RecordSuggestionNotification($count));
            } else {
                Log::info('Record matching: team has no owner to notify', ['team_id' => $teamId, 'new_suggestions' => $count]);
            }
        }

        if ($unowned > 0) {
            Log::info('Record matching: new suggestions with no owning team; notification skipped', ['count' => $unowned]);
        }

        Log::info('Record matching job completed', [
            'total_matches_found' => $totalMatches,
            'persons_processed' => $persons->count(),
        ]);
    }
}
