<?php

namespace Tests\Unit\Services;

use App\Models\DocumentTranscription;
use App\Models\TranscriptionCorrection;
use App\Models\User;
use App\Models\Team;
use App\Services\HandwritingRecognitionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HandwritingRecognitionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected HandwritingRecognitionService $service;
    protected User $user;
    protected Team $team;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new HandwritingRecognitionService();
        
        // Create test user and team
        $this->user = User::factory()->create();
        $this->team = Team::factory()->create(['user_id' => $this->user->id]);
        $this->user->current_team_id = $this->team->id;
        $this->user->save();

        // Set up fake storage
        Storage::fake('public');
    }

    public function testProcessDocumentCreatesTranscription(): void
    {
        $file = UploadedFile::fake()->image('document.jpg');

        $transcription = $this->service->processDocument($file, $this->user, $this->team->id);

        $this->assertInstanceOf(DocumentTranscription::class, $transcription);
        $this->assertEquals($this->team->id, $transcription->team_id);
        $this->assertEquals($this->user->id, $transcription->user_id);
        $this->assertEquals('document.jpg', $transcription->original_filename);
        $this->assertNotNull($transcription->document_path);
        
        // Verify file was stored
        Storage::disk('public')->assertExists($transcription->document_path);
    }

    public function testProcessDocumentWithFallbackOCR(): void
    {
        $file = UploadedFile::fake()->image('document.jpg');

        $transcription = $this->service->processDocument($file, $this->user, $this->team->id);

        $this->assertEquals('completed', $transcription->status);
        $this->assertNotNull($transcription->raw_transcription);
        $this->assertNotNull($transcription->processed_at);
        $this->assertIsArray($transcription->metadata);
    }

    public function testApplyCorrectionCreatesRecord(): void
    {
        $transcription = DocumentTranscription::factory()->create([
            'team_id' => $this->team->id,
            'user_id' => $this->user->id,
            'raw_transcription' => 'Original text',
        ]);

        $correctedText = 'Corrected text';
        $correction = $this->service->applyCorrection(
            $transcription,
            $this->user,
            $correctedText,
            'Original text'
        );

        $this->assertInstanceOf(TranscriptionCorrection::class, $correction);
        $this->assertEquals($transcription->id, $correction->document_transcription_id);
        $this->assertEquals($this->user->id, $correction->user_id);
        $this->assertEquals('Original text', $correction->original_text);
        $this->assertEquals($correctedText, $correction->corrected_text);

        // Verify transcription was updated
        $transcription->refresh();
        $this->assertEquals($correctedText, $transcription->corrected_transcription);
    }

    public function testGetTeamStatsReturnsCorrectData(): void
    {
        // Create various transcriptions
        DocumentTranscription::factory()->count(5)->create([
            'team_id' => $this->team->id,
            'status' => 'completed',
        ]);

        DocumentTranscription::factory()->count(2)->create([
            'team_id' => $this->team->id,
            'status' => 'pending',
        ]);

        DocumentTranscription::factory()->count(1)->create([
            'team_id' => $this->team->id,
            'status' => 'failed',
        ]);

        $stats = $this->service->getTeamStats($this->team->id);

        $this->assertEquals(8, $stats['total_transcriptions']);
        $this->assertEquals(5, $stats['completed_transcriptions']);
        $this->assertEquals(2, $stats['pending_transcriptions']);
        $this->assertEquals(1, $stats['failed_transcriptions']);
    }

    public function testGetCurrentTranscriptionReturnsCorrectValue(): void
    {
        $transcription = DocumentTranscription::factory()->create([
            'raw_transcription' => 'Raw text',
            'corrected_transcription' => null,
        ]);

        $this->assertEquals('Raw text', $transcription->getCurrentTranscription());

        $transcription->corrected_transcription = 'Corrected text';
        $transcription->save();

        $this->assertEquals('Corrected text', $transcription->getCurrentTranscription());
    }

    public function testHasCorrectionsReturnsTrueWhenCorrected(): void
    {
        $transcription = DocumentTranscription::factory()->create([
            'raw_transcription' => 'Raw text',
            'corrected_transcription' => 'Corrected text',
        ]);

        $this->assertTrue($transcription->hasCorrections());
    }

    public function testGetConfidenceScoreReturnsCorrectValue(): void
    {
        $transcription = DocumentTranscription::factory()->create([
            'metadata' => ['confidence' => 0.85],
        ]);

        $this->assertEquals(0.85, $transcription->getConfidenceScore());
    }
}
