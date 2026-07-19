<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Achievement;
use App\Models\Person;
use App\Models\PersonPhoto;
use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Tests\TestCase;

/**
 * The photo counter returned a hardcoded 0 with a "Placeholder" comment, so the
 * two photo achievements — photo_archivist (5 photos) and memory_keeper (25) —
 * could never be earned no matter how many photos a researcher uploaded, and
 * their progress bars sat permanently at zero.
 *
 * Showing someone a goal whose counter cannot move is the dishonesty here. It
 * under-reports rather than invents, but it is the same class: a number
 * presented as measured that is not.
 */
class PhotoAchievementProgressTest extends TestCase
{
    use RefreshDatabase;

    public function test_photos_in_the_users_team_are_counted(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        $this->photosFor($user->current_team_id, 3);

        $this->assertSame(3, $this->photoCount($user));
    }

    /**
     * The scoping test that actually proves something.
     *
     * Run authenticated, this passes even with the explicit team filter removed,
     * because BelongsToTenant's global scope is already filtering on the logged
     * in user's team. So it is run WITHOUT authentication, which is the real
     * hazard: UserLeveledUpListener implements ShouldQueue and calls into the
     * counters from a worker with no auth context, where the global scope
     * returns early and the explicit filter is the only thing separating tenants.
     */
    public function test_photos_from_another_team_are_excluded_with_no_authenticated_user(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $other = User::factory()->withPersonalTeam()->create();

        $this->photosFor($user->current_team_id, 2);
        $this->photosFor($other->current_team_id, 7);

        $this->assertGuest();
        $this->assertSame(2, $this->photoCount($user));
    }

    /**
     * latestTeam() is belongsTo(Team, 'current_team_id'), so the old
     * `current_team_id ?? latestTeam?->id` fallback tested the same column
     * twice and could never resolve. A user without a current team counted
     * `team_id is null` — orphan rows belonging to nobody.
     */
    public function test_a_user_without_a_current_team_counts_their_owned_team(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $teamId = $user->current_team_id;

        $this->photosFor($teamId, 4);

        $user->forceFill(['current_team_id' => null])->save();

        $this->assertSame(4, $this->photoCount($user->fresh()));
    }

    public function test_a_photo_achievement_becomes_earnable(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        $this->photosFor($user->current_team_id, 5);

        // photo_archivist requires 5. Previously this could never be true.
        $method = new ReflectionMethod(GamificationService::class, 'checkAchievementRequirements');
        $method->setAccessible(true);

        $achievement = new Achievement(['key' => 'photo_archivist', 'requirements' => []]);

        $this->assertTrue($method->invoke(new GamificationService, $user, $achievement));
    }

    private function photosFor(int $teamId, int $count): void
    {
        $person = Person::factory()->create(['team_id' => $teamId]);

        PersonPhoto::factory()->count($count)->create([
            'team_id' => $teamId,
            'person_id' => $person->id,
        ]);
    }

    private function photoCount(User $user): int
    {
        $method = new ReflectionMethod(GamificationService::class, 'getPhotoCount');
        $method->setAccessible(true);

        return $method->invoke(new GamificationService, $user);
    }
}
