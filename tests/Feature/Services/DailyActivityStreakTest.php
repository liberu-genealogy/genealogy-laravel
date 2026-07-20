<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\User;
use App\Models\UserPoint;
use App\Services\GamificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Tests\TestCase;

/**
 * The daily activity streak is a consecutive run of days, ending today, on which
 * the user earned points. Characterises that behaviour so it is preserved when
 * the per-day query loop is replaced by a single grouped query.
 */
final class DailyActivityStreakTest extends TestCase
{
    use RefreshDatabase;

    private function streak(User $user): int
    {
        $method = new ReflectionMethod(GamificationService::class, 'getDailyActivityStreak');

        return $method->invoke(new GamificationService, $user);
    }

    private function activityDaysAgo(User $user, int ...$offsets): void
    {
        foreach ($offsets as $offset) {
            $point = UserPoint::create([
                'user_id' => $user->id,
                'activity_type' => 'test',
                'points' => 1,
            ]);
            $point->created_at = now()->subDays($offset);
            $point->save();
        }
    }

    public function test_a_streak_ending_today_counts_each_consecutive_day(): void
    {
        $user = User::factory()->create();
        $this->activityDaysAgo($user, 0, 1, 2);

        $this->assertSame(3, $this->streak($user));
    }

    public function test_a_streak_breaks_at_the_first_gap(): void
    {
        $user = User::factory()->create();
        $this->activityDaysAgo($user, 0, 1, 3); // day 2 missing

        $this->assertSame(2, $this->streak($user));
    }

    public function test_repeated_activity_on_one_day_counts_once(): void
    {
        $user = User::factory()->create();
        $this->activityDaysAgo($user, 0, 0, 1);

        $this->assertSame(2, $this->streak($user));
    }

    public function test_a_streak_that_ended_yesterday_is_zero_today(): void
    {
        $user = User::factory()->create();
        $this->activityDaysAgo($user, 1, 2); // nothing today

        $this->assertSame(0, $this->streak($user));
    }
}
