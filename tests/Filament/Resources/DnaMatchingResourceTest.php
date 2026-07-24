<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\DnaMatchingResource;
use App\Models\DnaMatching;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DnaMatchingResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(DnaMatching::class, DnaMatchingResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = DnaMatchingResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $user = User::factory()->create();
        $matchUser = User::factory()->create();

        $dnaMatching = DnaMatching::factory()->create([
            'user_id' => $user->id,
            'match_id' => $matchUser->id,
            'match_name' => 'Test Match',
        ]);

        $this->assertDatabaseHas('dna_matchings', ['match_name' => 'Test Match']);

        $retrieved = DnaMatching::find($dnaMatching->id);
        $this->assertNotNull($retrieved);

        $dnaMatching->update(['match_name' => 'Updated Match']);
        $this->assertDatabaseHas('dna_matchings', ['match_name' => 'Updated Match']);

        $dnaMatching->delete();
        $this->assertDatabaseMissing('dna_matchings', ['id' => $dnaMatching->id]);
    }

    public function test_can_access_denied_for_non_premium_user(): void
    {
        config(['premium.enabled' => false]);
        $user = User::factory()->create(['is_premium' => false, 'trial_ends_at' => null]);
        Auth::login($user);

        $this->assertFalse(DnaMatchingResource::canAccess());
    }

    public function test_can_access_allowed_for_trialing_premium_user(): void
    {
        config(['premium.enabled' => false]);
        $user = User::factory()->create(['is_premium' => true, 'trial_ends_at' => now()->addDays(5)]);
        Auth::login($user);

        $this->assertTrue(DnaMatchingResource::canAccess());
    }

    public function test_can_access_allowed_when_premium_globally_enabled(): void
    {
        config(['premium.enabled' => true]);
        $user = User::factory()->create(['is_premium' => false]);
        Auth::login($user);

        $this->assertTrue(DnaMatchingResource::canAccess());
    }
}
