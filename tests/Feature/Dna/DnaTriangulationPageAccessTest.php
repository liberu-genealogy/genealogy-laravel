<?php

namespace Tests\Feature\Dna;

use App\Filament\App\Pages\DnaTriangulationPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DnaTriangulationPageAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_denied_for_non_premium_user(): void
    {
        config(['premium.enabled' => false]);
        $user = User::factory()->create(['is_premium' => false, 'trial_ends_at' => null]);
        Auth::login($user);

        $this->assertFalse(DnaTriangulationPage::canAccess());
    }

    public function test_can_access_allowed_for_trialing_premium_user(): void
    {
        config(['premium.enabled' => false]);
        $user = User::factory()->create(['is_premium' => true, 'trial_ends_at' => now()->addDays(5)]);
        Auth::login($user);

        $this->assertTrue(DnaTriangulationPage::canAccess());
    }

    public function test_can_access_allowed_when_premium_globally_enabled(): void
    {
        config(['premium.enabled' => true]);
        $user = User::factory()->create(['is_premium' => false]);
        Auth::login($user);

        $this->assertTrue(DnaTriangulationPage::canAccess());
    }
}
