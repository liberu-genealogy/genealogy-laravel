<?php

declare(strict_types=1);

namespace Tests\Unit\Services\RecordMatcher;

use App\Jobs\RunRecordMatchingJob;
use App\Models\Person;
use App\Services\RecordMatcher\RecordMatcherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
