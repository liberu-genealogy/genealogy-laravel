<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\DatabaseUpdateService;
use App\Models\Team;
use Mockery;

class DatabaseUpdateServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdateSubscriptionRecord()
    {
        $mockTeam = Mockery::mock('overload:' . Team::class);
        $subscriptionId = 'sub_test123';
        $newPlanId = 'plan_new123';

        // Scenario: Team found
        $mockTeam->shouldReceive('whereHas')->once()->andReturnSelf();
        $mockTeam->shouldReceive('first')->once()->andReturnSelf();
        $mockTeam->shouldReceive('subscriptions')->once()->andReturnSelf();
        $mockTeam->shouldReceive('updateOrCreate')->once()->andReturnSelf();

        $service = new DatabaseUpdateService();
        $result = $service->updateSubscriptionRecord($subscriptionId, $newPlanId);

        $this->assertEquals(['success' => true, 'message' => 'Subscription updated successfully.'], $result);

        // Scenario: Team not found
        $mockTeam->shouldReceive('whereHas')->once()->andReturnSelf();
        $mockTeam->shouldReceive('first')->once()->andReturn(null);

        $result = $service->updateSubscriptionRecord($subscriptionId, $newPlanId);

        $this->assertEquals(['success' => false, 'message' => 'Team not found.'], $result);
    }

    public function testCancelSubscriptionRecord()
    {
        $mockTeam = Mockery::mock('overload:' . Team::class);
        $subscriptionId = 'sub_cancel123';

        // Scenario: Team found
        $mockTeam->shouldReceive('whereHas')->once()->andReturnSelf();
        $mockTeam->shouldReceive('first')->once()->andReturnSelf();
        $mockTeam->shouldReceive('subscriptions')->once()->andReturnSelf();
        $mockTeam->shouldReceive('where')->once()->andReturnSelf();
        $mockTeam->shouldReceive('delete')->once()->andReturn(true);

        $service = new DatabaseUpdateService();
        $result = $service->cancelSubscriptionRecord($subscriptionId);

        $this->assertEquals(['success' => true, 'message' => 'Subscription cancelled successfully.'], $result);

        // Scenario: Team not found
        $mockTeam->shouldReceive('whereHas')->once()->andReturnSelf();
        $mockTeam->shouldReceive('first')->once()->andReturn(null);

        $result = $service->cancelSubscriptionRecord($subscriptionId);

        $this->assertEquals(['success' => false, 'message' => 'Team not found.'], $result);
    }
}
