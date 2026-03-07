<?php

namespace Tests\Unit\Services;

use App\Services\DatabaseUpdateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StripeSubscriptionServiceTest extends TestCase
{
    use RefreshDatabase;

    private DatabaseUpdateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DatabaseUpdateService();
    }

    public function testUpdateSubscription(): void
    {
        // When no team has the given subscription, updateSubscriptionRecord returns failure
        $result = $this->service->updateSubscriptionRecord('sub_nonexistent', 'plan_new');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('not found', $result['message']);
    }

    public function testCancelSubscription(): void
    {
        // When no team has the given subscription, cancelSubscriptionRecord returns failure
        $result = $this->service->cancelSubscriptionRecord('sub_nonexistent');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('not found', $result['message']);
    }
}
