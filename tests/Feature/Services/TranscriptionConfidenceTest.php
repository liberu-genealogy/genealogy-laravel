<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\DocumentTranscription;
use App\Models\User;
use App\Services\HandwritingRecognitionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Unlike the other "AI" services in this app, handwriting recognition is honest:
 * it calls Google Cloud Vision for real when configured, and its fallback returns
 * text that says outright it is a placeholder. Two things about the numbers around
 * it were not honest, though.
 */
class TranscriptionConfidenceTest extends TestCase
{
    use RefreshDatabase;

    private function transcription(int $teamId, float $confidence): DocumentTranscription
    {
        return DocumentTranscription::factory()->create([
            'team_id' => $teamId,
            'status' => 'completed',
            'metadata' => ['confidence' => $confidence],
        ]);
    }

    /**
     * Google Vision reports confidence on a 0-1 scale and it is stored that way,
     * but the only consumer renders this value straight into a "%" tile.
     */
    public function test_team_stats_reports_confidence_as_a_percentage(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        $teamId = $user->current_team_id;

        $this->transcription($teamId, 0.9);
        $this->transcription($teamId, 0.8);

        $stats = (new HandwritingRecognitionService)->getTeamStats($teamId);

        // Mean of 0.9 and 0.8 is 0.85 -> 85%, not "0.9%".
        $this->assertSame(85.0, $stats['avg_confidence']);
    }

    /**
     * The fallback claimed 0.75 confidence on text whose own content explains that
     * it is a placeholder, which then fed the team's average.
     *
     * The earlier fix changed that 0.75 to 0.0, which is still a claim: zero is a
     * measured value, and SQL AVG counts it. Nothing was read, so there is no
     * confidence to report at all — the value is absent, not zero.
     */
    public function test_the_placeholder_transcription_reports_no_confidence_at_all(): void
    {
        $method = new ReflectionMethod(HandwritingRecognitionService::class, 'performFallbackOCR');
        $method->setAccessible(true);

        $result = $method->invoke(new HandwritingRecognitionService, '/tmp/does-not-matter.jpg');

        $this->assertStringContainsString('placeholder transcription', $result['text']);
        $this->assertNull($result['confidence']);
    }

    /**
     * Previously this asserted 50.0 — a real 100%-confidence transcription plus a
     * placeholder averaging out to half. That test was named "does not inflate"
     * and commented "contributes nothing", but 50.0 is the proof that it did
     * contribute: an unread placeholder halved the team's reported accuracy.
     */
    public function test_a_placeholder_is_excluded_from_the_teams_average_entirely(): void
    {
        Storage::fake('public');
        config(['services.google_vision.api_key' => null]);

        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        $teamId = $user->current_team_id;

        $this->transcription($teamId, 1.0);

        // A real placeholder, produced by the unconfigured-install path — not a
        // fixture with the key pre-removed. Building it by hand would make this
        // test pass against the old code, which is what it is here to catch.
        (new HandwritingRecognitionService)->processDocument(
            UploadedFile::fake()->image('census.jpg'),
            $user,
            $teamId,
        );

        $stats = (new HandwritingRecognitionService)->getTeamStats($teamId);

        // The one measured transcription is the whole average. Previously the
        // placeholder contributed 0.0 and this returned 50.0 — an unread document
        // halving the team's reported accuracy.
        $this->assertSame(100.0, $stats['avg_confidence']);
        // It is still counted as a transcription; only the confidence is absent.
        $this->assertSame(2, $stats['total_transcriptions']);
    }

    public function test_avg_confidence_is_null_when_nothing_was_ever_measured(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        $teamId = $user->current_team_id;

        $this->unmeasuredTranscription($teamId);
        $this->unmeasuredTranscription($teamId);

        $stats = (new HandwritingRecognitionService)->getTeamStats($teamId);

        // Not 0.0 — reporting "0% average confidence" is itself a false claim
        // about accuracy when no measurement was ever taken.
        $this->assertNull($stats['avg_confidence']);
    }

    /**
     * Google Vision commonly returns text annotations with no per-word confidence.
     * That is an ordinary response, not an error — and the service substituted
     * 0.85 and reported it as the API's own measurement.
     */
    public function test_vision_annotations_without_confidence_report_none(): void
    {
        config(['services.google_vision.api_key' => 'test-key']);

        Http::fake([
            'vision.googleapis.com/*' => Http::response([
                'responses' => [[
                    'textAnnotations' => [
                        ['description' => "Elizabeth Fry\n1780"],
                        ['description' => 'Elizabeth'],
                    ],
                ]],
            ]),
        ]);

        $result = $this->runOcr();

        $this->assertSame("Elizabeth Fry\n1780", $result['text']);
        $this->assertNull($result['confidence']);
    }

    public function test_vision_confidences_are_averaged_when_present(): void
    {
        config(['services.google_vision.api_key' => 'test-key']);

        Http::fake([
            'vision.googleapis.com/*' => Http::response([
                'responses' => [[
                    'textAnnotations' => [
                        ['description' => 'Elizabeth Fry', 'confidence' => 0.9],
                        ['description' => 'Elizabeth', 'confidence' => 0.7],
                    ],
                ]],
            ]),
        ]);

        $result = $this->runOcr();

        $this->assertSame(0.8, $result['confidence']);
    }

    public function test_vision_finding_no_text_reports_no_confidence(): void
    {
        config(['services.google_vision.api_key' => 'test-key']);

        Http::fake([
            'vision.googleapis.com/*' => Http::response(['responses' => [[]]]),
        ]);

        $result = $this->runOcr();

        $this->assertSame('', $result['text']);
        // Nothing was detected, so there is nothing to be confident about. A
        // reported 0 would read as "detected text, certain it is wrong".
        $this->assertNull($result['confidence']);
    }

    public function test_a_processed_document_stores_no_confidence_key_when_unmeasured(): void
    {
        Storage::fake('public');
        config(['services.google_vision.api_key' => null]);

        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        $transcription = (new HandwritingRecognitionService)->processDocument(
            UploadedFile::fake()->image('census.jpg'),
            $user,
            $user->current_team_id,
        );

        $this->assertSame('completed', $transcription->status);
        // Absent, not null — and this assertion is the only thing holding that
        // invariant. Verified against both drivers: a stored JSON null extracts
        // to SQL NULL on SQLite (so AVG skips it) but stays a JSON null literal
        // on MariaDB, where AVG counts it as 0. Storing null would therefore keep
        // this suite green while averaging zeros into production confidence.
        $this->assertArrayNotHasKey('confidence', $transcription->fresh()->metadata);
        $this->assertNull($transcription->fresh()->getConfidenceScore());
    }

    private function unmeasuredTranscription(int $teamId): DocumentTranscription
    {
        return DocumentTranscription::factory()->create([
            'team_id' => $teamId,
            'status' => 'completed',
            'metadata' => ['language' => 'en'],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function runOcr(): array
    {
        Storage::fake('public');
        $path = Storage::disk('public')->path('ocr-fixture.jpg');
        file_put_contents($path, 'not-a-real-image');

        $method = new ReflectionMethod(HandwritingRecognitionService::class, 'performOCR');
        $method->setAccessible(true);

        return $method->invoke(new HandwritingRecognitionService, $path);
    }

    public function test_team_stats_are_scoped_to_the_team(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $other = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        $this->transcription($user->current_team_id, 0.5);
        $this->transcription($other->current_team_id, 1.0);

        $this->assertSame(1, (new HandwritingRecognitionService)->getTeamStats($user->current_team_id)['total_transcriptions']);
    }
}
