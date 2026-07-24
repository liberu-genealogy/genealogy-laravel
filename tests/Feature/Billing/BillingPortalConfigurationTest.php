<?php

declare(strict_types=1);

namespace Tests\Feature\Billing;

use App\Models\SubscriptionPortalConfig;
use App\Services\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The billing-portal Configuration that enables monthly<->yearly plan switching
 * is owned by the app (ADR 0003 managed-object pattern). The Stripe SDK sits
 * behind protected seams; this fake overrides them so the create/reuse/auto-heal
 * logic runs with zero Stripe HTTP. livemode pinned to test mode.
 */
final class FakePortalSubscriptionService extends SubscriptionService
{
    public array $portalUpserts = [];

    public int $portalCalls = 0;

    public int $productCalls = 0;

    public array $created = [];

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

    protected function archiveStripePrice(string $priceId): void {}

    protected function upsertStripePortalConfiguration(?string $existingId, array $features): string
    {
        $this->portalCalls++;
        $this->portalUpserts[] = ['existingId' => $existingId, 'features' => $features];

        return 'bpc_fake_'.$this->portalCalls;
    }
}

final class BillingPortalConfigurationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('subscription.premium.amounts.month', 299);
        config()->set('subscription.premium.amounts.year', 2999);
        config()->set('cashier.currency', 'usd');
    }

    public function test_configuration_enables_plan_switch_over_both_managed_prices(): void
    {
        $service = new FakePortalSubscriptionService;

        $configId = $service->resolveBillingPortalConfiguration();

        $this->assertSame('bpc_fake_1', $configId);
        $this->assertSame(1, $service->portalCalls);
        $this->assertNull($service->portalUpserts[0]['existingId']);

        $update = $service->portalUpserts[0]['features']['subscription_update'];
        $this->assertTrue($update['enabled']);
        $this->assertSame(['price'], $update['default_allowed_updates']);

        // Both managed prices (month = price_fake_1, year = price_fake_2), each
        // under its own product, must be offered so the customer can switch.
        $offeredPrices = array_merge(...array_column($update['products'], 'prices'));
        $this->assertContains('price_fake_1', $offeredPrices);
        $this->assertContains('price_fake_2', $offeredPrices);

        $this->assertTrue($service->portalUpserts[0]['features']['subscription_cancel']['enabled']);

        $this->assertDatabaseHas('subscription_portal_configs', [
            'livemode' => false,
            'stripe_configuration_id' => 'bpc_fake_1',
            'stripe_month_price_id' => 'price_fake_1',
            'stripe_year_price_id' => 'price_fake_2',
        ]);
    }

    public function test_configuration_is_reused_when_prices_unchanged(): void
    {
        $service = new FakePortalSubscriptionService;

        $first = $service->resolveBillingPortalConfiguration();
        $second = $service->resolveBillingPortalConfiguration();

        $this->assertSame($first, $second);
        $this->assertSame(1, $service->portalCalls); // no second Stripe call
        $this->assertSame(1, SubscriptionPortalConfig::count());
    }

    public function test_configuration_auto_heals_when_a_managed_price_changes(): void
    {
        $service = new FakePortalSubscriptionService;
        $service->resolveBillingPortalConfiguration();

        // A changed configured amount forces a fresh managed Price, so the stored
        // configuration now references a stale price and must be updated in place.
        config()->set('subscription.premium.amounts.month', 399);
        $service->resolveBillingPortalConfiguration();

        $this->assertSame(2, $service->portalCalls);
        $this->assertSame('bpc_fake_1', $service->portalUpserts[1]['existingId']); // update, not create
    }

    public function test_proration_behavior_comes_from_config(): void
    {
        config()->set('subscription.premium.portal_proration', 'none');
        $service = new FakePortalSubscriptionService;

        $service->resolveBillingPortalConfiguration();

        $this->assertSame(
            'none',
            $service->portalUpserts[0]['features']['subscription_update']['proration_behavior']
        );
    }
}
