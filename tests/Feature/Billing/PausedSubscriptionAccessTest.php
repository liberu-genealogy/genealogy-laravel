<?php

declare(strict_types=1);

namespace Tests\Feature\Billing;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Subscription;
use Tests\TestCase;

/**
 * ADR 0002: a paused subscription revokes premium access. Stripe leaves a paused
 * subscription's status as "active", so isPremium() must consult the local
 * paused_at marker rather than trusting subscribed() alone.
 */
final class PausedSubscriptionAccessTest extends TestCase
{
    use RefreshDatabase;

    private function subscribe(User $user): Subscription
    {
        return $user->subscriptions()->create([
            'type' => 'premium',
            'stripe_id' => 'sub_'.$user->id,
            'stripe_status' => 'active',
            'stripe_price' => 'price_premium_monthly',
            'quantity' => 1,
        ]);
    }

    public function test_an_active_subscription_is_premium(): void
    {
        $user = User::factory()->create();
        $this->subscribe($user);

        $this->assertTrue($user->fresh()->isPremium());
    }

    public function test_a_paused_subscription_is_not_premium(): void
    {
        $user = User::factory()->create();
        $subscription = $this->subscribe($user);

        $subscription->update(['paused_at' => now()]);

        $this->assertFalse($user->fresh()->isPremium());
    }

    public function test_resuming_from_pause_restores_premium(): void
    {
        $user = User::factory()->create();
        $subscription = $this->subscribe($user);
        $subscription->update(['paused_at' => now()]);

        $subscription->update(['paused_at' => null]);

        $this->assertTrue($user->fresh()->isPremium());
    }
}
