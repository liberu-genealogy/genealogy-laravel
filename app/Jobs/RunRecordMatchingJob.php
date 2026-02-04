<?php

namespace App\Jobs;

use ReflectionClass;
use App\Models\Person;
use App\Services\RecordMatcher\Providers\ExampleProvider;
use App\Services\RecordMatcher\RecordMatcherService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunRecordMatchingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1200;

    public function handle(RecordMatcherService $matcher)
    {
        // Providers could be defined in config; for now use ExampleProvider
        $providers = [
            new ExampleProvider(),
            // Add real providers via DI/config
        ];

        // Fetch a sample of persons to run against (could be queued per-person)
        $persons = Person::whereNotNull('last_name')->limit(200)->get();

        foreach ($persons as $person) {
            foreach ($providers as $provider) {
                $candidates = $provider->search($person);
                $scored = $matcher->scoreCandidates($person, $candidates);

                foreach ($scored as $entry) {
                    $candidate = $entry['candidate'];
                    $score = $entry['score'];
                    // Only persist suggestions above a threshold (e.g., 0.45)
                    if ($score >= config('ai_record_match.min_confidence', 0.45)) {
                        $matcher->persistSuggestion($person->id, (new ReflectionClass($provider))->getShortName(), $candidate, $score);
                    }
                }
            }
        }
    }
}
