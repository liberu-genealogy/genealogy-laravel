<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\DocumentTranscription;
use App\Models\User;
use App\Services\HandwritingRecognitionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
     */
    public function test_the_placeholder_transcription_claims_no_confidence(): void
    {
        $method = new ReflectionMethod(HandwritingRecognitionService::class, 'performFallbackOCR');
        $method->setAccessible(true);

        $result = $method->invoke(new HandwritingRecognitionService, '/tmp/does-not-matter.jpg');

        $this->assertStringContainsString('placeholder transcription', $result['text']);
        $this->assertSame(0.0, $result['confidence']);
    }

    public function test_a_placeholder_does_not_inflate_the_teams_average(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        $teamId = $user->current_team_id;

        // One real transcription, one unconfigured-install placeholder.
        $this->transcription($teamId, 1.0);
        $this->transcription($teamId, 0.0);

        $stats = (new HandwritingRecognitionService)->getTeamStats($teamId);

        // The placeholder contributes nothing rather than 0.75.
        $this->assertSame(50.0, $stats['avg_confidence']);
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
