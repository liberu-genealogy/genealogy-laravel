<?php

namespace App\Jobs;

use ReflectionClass;
use App\Models\Person;
use App\Services\RecordMatcher\Providers\ExampleProvider;
use App\Services\RecordMatcher\Providers\MyHeritageProvider;
use App\Services\RecordMatcher\Providers\AncestryProvider;
use App\Services\RecordMatcher\Providers\FamilySearchProvider;
use App\Services\RecordMatcher\RecordMatcherService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunRecordMatchingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1200;

    public function handle(RecordMatcherService $matcher)
    {
        // Initialize providers based on configuration
        $providers = [];

        // Add MyHeritage provider if configured
        $myHeritage = new MyHeritageProvider();
        if ($myHeritage->isConfigured()) {
            $providers[] = $myHeritage;
        }

        // Add Ancestry provider if configured
        $ancestry = new AncestryProvider();
        if ($ancestry->isConfigured()) {
            $providers[] = $ancestry;
        }

        // Add FamilySearch provider if configured
        $familySearch = new FamilySearchProvider();
        if ($familySearch->isConfigured()) {
            $providers[] = $familySearch;
        }

        // If no providers configured, use example provider for testing
        if (empty($providers)) {
            Log::warning('No genealogy providers configured, using example provider');
            $providers[] = new ExampleProvider();
        }

        Log::info('Record matching job started', [
            'providers' => array_map(fn($p) => (new ReflectionClass($p))->getShortName(), $providers),
        ]);

        // Fetch a sample of persons to run against (could be queued per-person)
        $persons = Person::whereNotNull('last_name')->limit(200)->get();

        $totalMatches = 0;

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
                            $matcher->persistSuggestion(
                                $person->id, 
                                (new ReflectionClass($provider))->getShortName(), 
                                $candidate, 
                                $score
                            );
                            $totalMatches++;
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Record matching failed for person', [
                        'person_id' => $person->id,
                        'provider' => (new ReflectionClass($provider))->getShortName(),
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        Log::info('Record matching job completed', [
            'total_matches_found' => $totalMatches,
            'persons_processed' => $persons->count(),
        ]);
    }
}
