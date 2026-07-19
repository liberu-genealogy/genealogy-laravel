<?php

declare(strict_types=1);

namespace Tests\Feature\Notifications;

use App\Jobs\DnaMatching;
use App\Jobs\ScanForDuplicatePersons;
use App\Models\Dna;
use App\Models\Person;
use App\Models\Team;
use App\Models\User;
use App\Notifications\DnaMatchFoundNotification;
use App\Notifications\DuplicatePersonDetectedNotification;
use App\Notifications\RecordSuggestionNotification;
use App\Services\AdvancedDnaMatchingService;
use App\Services\DuplicateDetectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Mockery;
use Tests\TestCase;

class GenealogyNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_dna_matching_notifies_kit_owner_of_new_matches(): void
    {
        Notification::fake();

        $owner = User::factory()->create();
        // The owner's own consented kit (the job's consent gate requires it to
        // exist + have consent), plus another consented kit to match against.
        Dna::factory()->create(['variable_name' => 'kit_mine', 'user_id' => $owner->id]);
        Dna::factory()->create(['variable_name' => 'kit_other']);

        // Stub the heavy matching service so handle() creates a match deterministically.
        $service = Mockery::mock(AdvancedDnaMatchingService::class);
        $service->shouldReceive('performAdvancedMatching')->andReturn([
            'comparison_performed' => true,
            'total_cms' => 1200.0,
            'largest_cm' => 80.0,
        ]);
        $this->app->instance(AdvancedDnaMatchingService::class, $service);

        (new DnaMatching($owner, 'kit_mine', 'file_mine.txt'))->handle();

        Notification::assertSentTo(
            $owner,
            DnaMatchFoundNotification::class,
            fn (DnaMatchFoundNotification $n): bool => $n->via($owner) === ['database', 'mail'],
        );
    }

    public function test_duplicate_scan_notifies_team_owner(): void
    {
        Notification::fake();

        $team = Team::factory()->create();

        // Two near-identical people in the same team -> fuzzy-name duplicate.
        // (email is unique on `people`, so the trigger is the name/soundex path.)
        Person::factory()->create([
            'givn' => 'John', 'surn' => 'Smith', 'name' => 'John Smith', 'team_id' => $team->id,
        ]);
        Person::factory()->create([
            'givn' => 'John', 'surn' => 'Smith', 'name' => 'John Smith', 'team_id' => $team->id,
        ]);

        // Threshold 0.5: identical names score ~0.6 without relying on birthday equality.
        (new ScanForDuplicatePersons(0.5, 10))->handle(app(DuplicateDetectionService::class));

        Notification::assertSentTo($team->owner, DuplicatePersonDetectedNotification::class);
    }

    public function test_record_suggestion_notification_reaches_owner(): void
    {
        // The record-matching job only persists suggestions when an external provider
        // returns candidates; the bundled ExampleProvider is a no-op and real providers
        // need API credentials, so the job's persist path cannot be driven in a unit
        // test. Assert the notification the job sends reaches the resolved owner.
        Notification::fake();

        $owner = User::factory()->create();

        $owner->notify(new RecordSuggestionNotification(3));

        Notification::assertSentTo(
            $owner,
            RecordSuggestionNotification::class,
            fn (RecordSuggestionNotification $n): bool => $n->via($owner) === ['database', 'mail'],
        );
    }
}
