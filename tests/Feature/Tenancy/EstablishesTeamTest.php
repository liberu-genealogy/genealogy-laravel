<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Jobs\Concerns\EstablishesTeam;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use RuntimeException;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * The mechanism single-team jobs use to run with a tenant established.
 *
 * These call it the way a worker does — no authenticated user, sometimes a
 * permission team left over from a previous job — and check that it both
 * establishes the right team for the duration and leaves nothing behind. The
 * leak this guards against only appears across jobs in one long-lived process,
 * which is why the checks run two calls in the same test rather than relying on
 * separate methods the framework would reset between.
 */
class EstablishesTeamTest extends TestCase
{
    use RefreshDatabase;

    private object $runner;

    protected function setUp(): void
    {
        parent::setUp();

        // A throwaway object that uses the trait, standing in for a job.
        $this->runner = new class
        {
            use EstablishesTeam;

            public function run(User $user, int $teamId, callable $callback): mixed
            {
                return $this->asTeamMember($user, $teamId, $callback);
            }
        };
    }

    public function test_the_callback_runs_scoped_to_the_given_team(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        Auth::logout();

        // A person the tenant scope would only show to that team.
        $this->runner->run($owner, $owner->current_team_id, function () use ($owner): void {
            Person::factory()->create();

            $this->assertSame($owner->current_team_id, auth()->user()?->current_team_id);
        });

        $this->assertSame(
            $owner->current_team_id,
            Person::withoutGlobalScopes()->latest('id')->first()->team_id,
            'The row a scoped job created did not carry the team.',
        );
    }

    public function test_auth_and_permission_team_are_restored_even_when_the_callback_throws(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        Auth::logout();
        app(PermissionRegistrar::class)->setPermissionsTeamId(null);

        try {
            $this->runner->run($user, $user->current_team_id, function (): void {
                throw new RuntimeException('boom');
            });
            $this->fail('The callback should have thrown.');
        } catch (RuntimeException) {
            // expected
        }

        $this->assertNull(Auth::user(), 'A failed job left its user authenticated in the worker.');
        $this->assertNull(
            app(PermissionRegistrar::class)->getPermissionsTeamId(),
            'A failed job left its permission team set for the next job.',
        );
    }

    public function test_two_teams_in_one_process_do_not_cross_contaminate(): void
    {
        $userA = User::factory()->withPersonalTeam()->create();
        $userB = User::factory()->withPersonalTeam()->create();
        Auth::logout();

        // Simulate the worker having last served team A.
        app(PermissionRegistrar::class)->setPermissionsTeamId($userA->current_team_id);

        $this->runner->run($userB, $userB->current_team_id, function () use ($userB): void {
            Person::factory()->create();
            $this->assertSame($userB->current_team_id, auth()->user()?->current_team_id, 'Job B inherited job A\'s user.');
        });

        // The row is B's, and the permission team is back to what it was before
        // B ran — not B's, and not leaked onward.
        $this->assertSame($userB->current_team_id, Person::withoutGlobalScopes()->latest('id')->first()->team_id);
        $this->assertSame(
            $userA->current_team_id,
            app(PermissionRegistrar::class)->getPermissionsTeamId(),
            'Job B did not restore the permission team it found.',
        );
    }
}
