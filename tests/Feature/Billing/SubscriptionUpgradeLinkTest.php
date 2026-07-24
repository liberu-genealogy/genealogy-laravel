<?php

namespace Tests\Feature\Billing;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionUpgradeLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_upgrade_cta_points_at_tenant_scoped_subscription_page(): void
    {
        $user = User::factory()->withPersonalTeam()->create([
            'is_premium' => false,
            'trial_ends_at' => null,
        ]);
        $this->actingAs($user);

        $response = $this->get('/subscription');

        $response->assertOk();
        // The authenticated "Upgrade to premium" CTA must target the real
        // tenant-scoped Filament page, not the un-tenanted /app/subscription
        // path (which 404s because the app panel is tenant-scoped).
        $response->assertSee(
            route('filament.app.pages.subscription', ['tenant' => $user->currentTeam]),
            false
        );
        $response->assertDontSee('/app/subscription', false);
    }
}
