<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserProgress;
use App\Services\GamificationService;
use Database\Seeders\AchievementSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use ReflectionMethod;
use Tests\TestCase;

/**
 * An achievement whose key has no corresponding logic used to be presented as a
 * goal anyway: progress fell through to 0 and the target fell back to 1, so a
 * researcher saw "0 / 1" forever. The numerator was frozen — the requirement
 * check has a matching fallback that returns false — and the denominator was
 * invented. Nothing about that display was measured, and nothing surfaced it:
 * no log, no error, no admin warning.
 *
 * This was assumed latent — a hazard waiting on someone to seed a key without
 * adding the matching arm. The seeded-key guard below found it live: two of the
 * nineteen shipped achievements, "first person added" and "first family
 * created", were in the requirement check but absent from the progress
 * calculation. The application knew how to award them and not how to track
 * them, so both sat at 0 / 1 for every researcher until the instant they
 * unlocked. Their arms are now present.
 *
 * "Unhandled" is now distinguishable from "handled, no progress yet", rather
 * than both collapsing to zero.
 */
class UnrecognisedAchievementKeyTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_unrecognised_key_is_not_presented_as_a_goal(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Achievement::create([
            'key' => 'no_such_achievement',
            'name' => 'Mystery',
            'description' => 'Seeded without matching logic.',
            'icon' => 'heroicon-o-question-mark-circle',
            'category' => 'tree',
            'points' => 10,
            'requirements' => [],
            'badge_color' => 'gray',
            'is_active' => true,
        ]);

        (new GamificationService)->checkAchievements($user);

        // Previously this wrote a UserProgress row at 0 / 1.
        $this->assertSame(0, UserProgress::where('user_id', $user->id)->count());
    }

    public function test_an_unrecognised_key_is_reported_rather_than_failing_silently(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Log::spy();

        Achievement::create([
            'key' => 'no_such_achievement',
            'name' => 'Mystery',
            'description' => 'Seeded without matching logic.',
            'icon' => 'heroicon-o-question-mark-circle',
            'category' => 'tree',
            'points' => 10,
            'requirements' => [],
            'badge_color' => 'gray',
            'is_active' => true,
        ]);

        (new GamificationService)->checkAchievements($user);

        Log::shouldHaveReceived('warning')
            ->withArgs(fn (string $message, array $context = []): bool => ($context['achievement_key'] ?? null) === 'no_such_achievement')
            ->atLeast()->once();
    }

    /**
     * The guard that makes the above impossible to ship. Every achievement the
     * application seeds must have logic behind it; a key added to the database
     * without a matching arm fails here rather than in front of a researcher.
     */
    public function test_every_seeded_achievement_key_has_logic_behind_it(): void
    {
        $this->seed(AchievementSeeder::class);

        $service = new GamificationService;
        $method = new ReflectionMethod(GamificationService::class, 'calculateCurrentProgress');
        $method->setAccessible(true);

        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        $unhandled = Achievement::all()
            ->filter(fn (Achievement $a): bool => $method->invoke($service, $user, $a) === null)
            ->pluck('key')
            ->all();

        $this->assertSame([], $unhandled, 'Seeded achievement keys with no progress logic: '.implode(', ', $unhandled));
    }

    public function test_a_recognised_key_still_tracks_progress(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Achievement::create([
            'key' => 'family_builder',
            'name' => 'Family Builder',
            'description' => 'Add ten people.',
            'icon' => 'heroicon-o-users',
            'category' => 'tree',
            'points' => 10,
            'requirements' => ['count' => 10],
            'badge_color' => 'green',
            'is_active' => true,
        ]);

        (new GamificationService)->checkAchievements($user);

        $progress = UserProgress::where('user_id', $user->id)->first();

        $this->assertNotNull($progress, 'A key with logic must still be tracked.');
        $this->assertSame(10, $progress->target_progress, 'The target comes from the requirement, not a fallback of 1.');
    }
}
