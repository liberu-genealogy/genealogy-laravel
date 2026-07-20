<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\FacialRecognitionReview;
use App\Models\Person;
use App\Models\PersonPhoto;
use App\Models\PhotoTag;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * The review interface must distinguish a match whose confidence the provider
 * did not report (stored null) from one it reported as zero. number_format(null)
 * would print "0.0%" — reprinting the very fabrication the storage fix removed —
 * so the null case renders "not reported" instead.
 */
final class FacialRecognitionReviewConfidenceTest extends TestCase
{
    use RefreshDatabase;

    private function pendingTagWithConfidence(mixed $confidence): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);
        $user->current_team_id = $team->id;
        $user->save();
        $this->actingAs($user);

        $person = Person::factory()->create(['team_id' => $team->id, 'givn' => 'Ada', 'surn' => 'Lovelace']);
        $photo = PersonPhoto::factory()->create(['team_id' => $team->id]);

        PhotoTag::factory()->pending()->create([
            'photo_id' => $photo->id,
            'person_id' => $person->id,
            'team_id' => $team->id,
            'confidence' => $confidence,
        ]);
    }

    public function test_an_unreported_confidence_reads_as_not_reported(): void
    {
        $this->pendingTagWithConfidence(null);

        Livewire::test(FacialRecognitionReview::class)
            ->assertSee('not reported')
            ->assertDontSee('0.0%');
    }

    public function test_a_reported_confidence_reads_as_a_percentage(): void
    {
        $this->pendingTagWithConfidence(0.9);

        Livewire::test(FacialRecognitionReview::class)
            ->assertDontSee('not reported');
    }
}
