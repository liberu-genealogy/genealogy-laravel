<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Person;
use App\Models\User;
use App\Models\UserPoint;
use App\Services\GamificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamificationServiceTest extends TestCase
{
    use RefreshDatabase;

    private GamificationService $service;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GamificationService;
        $this->user = User::factory()->withPersonalTeam()->create();
    }

    public function test_service_can_be_instantiated(): void
    {
        $this->assertInstanceOf(GamificationService::class, $this->service);
    }

    public function test_award_points_creates_user_point_record(): void
    {
        $point = $this->service->awardPoints($this->user, 'test_activity', 50, 'Test description');

        $this->assertInstanceOf(UserPoint::class, $point);
        $this->assertSame(50, $point->points);
        $this->assertSame('test_activity', $point->activity_type);
        $this->assertDatabaseHas('user_points', [
            'user_id' => $this->user->id,
            'points' => 50,
            'activity_type' => 'test_activity',
        ]);
    }

    public function test_award_points_increments_user_total_points(): void
    {
        $initialPoints = (int) ($this->user->total_points ?? 0);

        $this->service->awardPoints($this->user, 'test_activity', 100);

        $this->assertGreaterThanOrEqual($initialPoints + 100, $this->user->fresh()->total_points);
    }

    public function test_award_points_multiple_times_accumulates(): void
    {
        $this->service->awardPoints($this->user, 'activity_1', 30);
        $this->service->awardPoints($this->user, 'activity_2', 70);

        $this->assertGreaterThanOrEqual(100, $this->user->fresh()->total_points);
    }

    public function test_get_leaderboard_returns_array(): void
    {
        $result = $this->service->getLeaderboard(10, 'all_time');

        $this->assertIsArray($result);
    }

    public function test_get_leaderboard_respects_limit(): void
    {
        User::factory()->count(5)->create(['show_on_leaderboard' => true, 'total_points' => 10]);

        $result = $this->service->getLeaderboard(3, 'all_time');

        $this->assertLessThanOrEqual(3, count($result));
    }

    public function test_get_user_stats_returns_expected_keys(): void
    {
        $stats = $this->service->getUserStats($this->user);

        $this->assertArrayHasKey('total_points', $stats);
        $this->assertArrayHasKey('level_info', $stats);
        $this->assertArrayHasKey('achievements_count', $stats);
        $this->assertArrayHasKey('leaderboard_rank', $stats);
    }

    public function test_check_achievements_returns_array(): void
    {
        $result = $this->service->checkAchievements($this->user, 'test_activity');

        $this->assertIsArray($result);
    }

    public function test_award_points_with_related_model(): void
    {
        $person = Person::factory()->create();

        $point = $this->service->awardPoints($this->user, 'person_added', 10, 'Added person', [], $person);

        $this->assertSame($person->id, $point->related_model_id);
        $this->assertSame(Person::class, $point->related_model_type);
    }
}
