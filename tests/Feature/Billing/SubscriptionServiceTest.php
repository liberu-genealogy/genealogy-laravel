<?php

declare(strict_types=1);

namespace Tests\Feature\Billing;

use App\Models\SubscriptionPrice;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use RuntimeException;
use Tests\TestCase;

/**
 * The Stripe SDK is folded into SubscriptionService behind three protected
 * seams (createStripeProduct / createStripePrice / archiveStripePrice). This
 * fake overrides them so the managed-price logic (create, reuse, auto-heal)
 * can be exercised with zero Stripe HTTP. livemode is pinned to test mode.
 */
final class FakeStripeSubscriptionService extends SubscriptionService
{
    public array $created = [];

    public array $archived = [];

    public int $productCalls = 0;

    protected function livemode(): bool
    {
        return false;
    }

    protected function createStripeProduct(string $name): string
    {
        $this->productCalls++;

        return 'prod_fake_'.$this->productCalls;
    }

    protected function createStripePrice(string $productId, int $amount, string $currency, string $interval): string
    {
        $this->created[] = compact('productId', 'amount', 'currency', 'interval');

        return 'price_fake_'.count($this->created);
    }

    protected function archiveStripePrice(string $priceId): void
    {
        $this->archived[] = $priceId;
    }
}

final class SubscriptionServiceTest extends TestCase
{
    use RefreshDatabase;

    private function service(): FakeStripeSubscriptionService
    {
        return new FakeStripeSubscriptionService;
    }

    public function test_resolve_managed_price_creates_product_and_price_and_records_it(): void
    {
        config()->set('subscription.premium.amounts.month', 299);
        config()->set('cashier.currency', 'usd');

        $service = $this->service();
        $priceId = $service->resolveManagedPrice('month');

        $this->assertSame('price_fake_1', $priceId);
        $this->assertSame(1, $service->productCalls);
        $this->assertCount(1, $service->created);

        $this->assertDatabaseHas('subscription_prices', [
            'interval' => 'month',
            'livemode' => false,
            'stripe_price_id' => 'price_fake_1',
            'unit_amount' => 299,
            'currency' => 'usd',
        ]);
    }

    public function test_resolve_managed_price_reuses_existing_record_without_touching_stripe(): void
    {
        config()->set('subscription.premium.amounts.month', 299);
        config()->set('cashier.currency', 'usd');

        SubscriptionPrice::create([
            'interval' => 'month',
            'livemode' => false,
            'stripe_product_id' => 'prod_existing',
            'stripe_price_id' => 'price_existing',
            'unit_amount' => 299,
            'currency' => 'usd',
        ]);

        $service = $this->service();
        $priceId = $service->resolveManagedPrice('month');

        $this->assertSame('price_existing', $priceId);
        $this->assertSame(0, $service->productCalls);
        $this->assertCount(0, $service->created);
        $this->assertCount(0, $service->archived);
    }

    public function test_resolve_managed_price_auto_heals_when_amount_changes(): void
    {
        config()->set('cashier.currency', 'usd');
        SubscriptionPrice::create([
            'interval' => 'month',
            'livemode' => false,
            'stripe_product_id' => 'prod_existing',
            'stripe_price_id' => 'price_old',
            'unit_amount' => 299,
            'currency' => 'usd',
        ]);

        // Operator raises the price.
        config()->set('subscription.premium.amounts.month', 399);

        $service = $this->service();
        $priceId = $service->resolveManagedPrice('month');

        // New price created against the SAME product; old price archived.
        $this->assertSame('price_fake_1', $priceId);
        $this->assertSame(0, $service->productCalls, 'existing product should be reused');
        $this->assertSame('prod_existing', $service->created[0]['productId']);
        $this->assertSame(399, $service->created[0]['amount']);
        $this->assertSame(['price_old'], $service->archived);

        $this->assertDatabaseHas('subscription_prices', [
            'interval' => 'month',
            'stripe_price_id' => 'price_fake_1',
            'unit_amount' => 399,
        ]);
        $this->assertDatabaseMissing('subscription_prices', ['stripe_price_id' => 'price_old']);
    }

    public function test_resolve_managed_price_rejects_unknown_interval(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service()->resolveManagedPrice('weekly');
    }

    public function test_requires_card_reflects_config(): void
    {
        config()->set('subscription.premium.require_card', true);
        $this->assertTrue($this->service()->requiresCard());

        config()->set('subscription.premium.require_card', false);
        $this->assertFalse($this->service()->requiresCard());
    }

    public function test_checkout_trial_days_is_null_when_zero_and_value_otherwise(): void
    {
        config()->set('subscription.premium.trial_days', 0);
        $this->assertNull($this->service()->checkoutTrialDays());

        config()->set('subscription.premium.trial_days', 14);
        $this->assertSame(14, $this->service()->checkoutTrialDays());
    }

    public function test_local_trial_is_rejected_when_card_is_required(): void
    {
        config()->set('subscription.premium.require_card', true);
        $user = User::factory()->create();

        $this->expectException(RuntimeException::class);

        try {
            $this->service()->createPremiumSubscription($user);
        } finally {
            $this->assertFalse((bool) $user->fresh()->is_premium, 'no premium granted without a card');
        }
    }

    public function test_local_trial_is_granted_when_card_not_required(): void
    {
        config()->set('subscription.premium.require_card', false);
        config()->set('subscription.premium.trial_days', 14);
        $user = User::factory()->create();

        $result = $this->service()->createPremiumSubscription($user);

        $this->assertNull($result);
        $user->refresh();
        $this->assertTrue((bool) $user->is_premium);
        $this->assertNotNull($user->trial_ends_at);
    }
}
