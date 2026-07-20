<?php

declare(strict_types=1);

namespace Tests\Unit\Services\RecordMatcher;

use App\Jobs\RunRecordMatchingJob;
use App\Models\AIMatchModel;
use App\Models\AISuggestedMatch;
use App\Models\Person;
use App\Services\RecordMatcher\RecordMatcherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * The matcher scored the local person on $person->first_name/last_name/birth_place,
 * but GEDCOM import writes givn/surn/birthday_plac — the first_name/last_name
 * columns are never populated (and aren't fillable). So every name/place factor
 * scored zero on real data, and the job's candidate query
 * (whereNotNull('last_name')) selected nobody at all. These pin both to the
 * columns the data actually uses.
 */
class RecordMatcherServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_scores_a_matching_candidate_using_gedcom_columns(): void
    {
        $person = Person::factory()->create([
            'givn' => 'Ada',
            'surn' => 'Lovelace',
            'birth_year' => 1815,
            'birthday_plac' => 'London, England',
        ]);

        // Candidate uses the providers' normalised keys (first_name/last_name/...).
        $candidate = [
            'id' => 'x1',
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'birth_year' => 1815,
            'birth_place' => 'London, England',
        ];

        $scored = (new RecordMatcherService)->scoreCandidates($person, [$candidate]);

        // Pre-fix only birth_year scores (names/place null on the person) → ~0.19,
        // below the job's 0.45 threshold. Post-fix every factor hits → ~1.0.
        $this->assertGreaterThanOrEqual(0.45, $scored[0]['score']);
    }

    public function test_job_selects_people_by_the_populated_name_column(): void
    {
        // Factory persons mirror imported data: surn set, last_name null.
        Person::factory()->create();
        Log::spy();

        (new RunRecordMatchingJob)->handle(new RecordMatcherService);

        // Pre-fix the job filtered on last_name (always null) and processed 0
        // people; it must select on surn.
        Log::shouldHaveReceived('info')
            ->withArgs(fn (string $message, array $context = []): bool => $message === 'Record matching job completed'
                && ($context['persons_processed'] ?? 0) === 1)
            ->once();
    }

    public function test_the_job_skips_a_provider_that_reports_unavailable(): void
    {
        // A configured provider whose request fails returns Unavailable, not an
        // array. The job must recognise that and log the reason, rather than feed
        // it to the scorer (which would TypeError and read as a generic failure).
        Config::set('services.ancestry.api_key', 'configured');
        Config::set('services.myheritage.api_key', '');
        Config::set('services.familysearch.api_key', '');
        Http::fake(['*' => Http::response([], 500)]);
        Person::factory()->create(['surn' => 'Lovelace']);
        Log::spy();

        (new RunRecordMatchingJob)->handle(new RecordMatcherService);

        Log::shouldHaveReceived('warning')
            ->withArgs(fn (string $message, array $context = []): bool => $message === 'Record matching provider unavailable; skipped'
                && str_contains((string) ($context['reason'] ?? ''), 'request failed'))
            ->atLeast()->once();
        $this->assertDatabaseCount('ai_suggested_matches', 0);
    }

    public function test_learn_from_feedback_does_not_crash_on_the_parents_factor(): void
    {
        $person = Person::factory()->create(['surn' => 'Lovelace']);

        $match = AISuggestedMatch::create([
            'local_person_id' => $person->id,
            'provider' => 'Example',
            'external_record_id' => 'ext-1',
            'candidate_data' => ['id' => 'ext-1', 'first_name' => 'Ada', 'last_name' => 'Lovelace'],
            'confidence' => 0.9,
            'status' => 'pending',
        ]);

        $before = AIMatchModel::count();

        // The 'parents' weight key read $local->parents, but Person::parents()
        // returns a Collection, not a relation, so Eloquent threw a
        // LogicException — the whole feedback loop crashed before persisting.
        (new RecordMatcherService)->learnFromFeedback($match, 'confirm');

        // Completing the loop persists exactly one new weight snapshot.
        $this->assertSame($before + 1, AIMatchModel::count());
    }
}
