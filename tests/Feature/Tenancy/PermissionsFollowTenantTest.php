<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Models\Person;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * Permissions resolved against the team the user arrived on, not the team they
 * were looking at.
 *
 * Filament nests the tenant middleware group inside the authentication group,
 * so everything in the panel's auth middleware runs first. The middleware that
 * sets the permission library's team context lived there and read the user's
 * stored current team. Tenant identification ran afterwards, and only then did
 * the SwitchTeam listener update that column.
 *
 * For that one request, every role and permission check evaluated against the
 * previous team while the interface rendered the new one — so the panel could
 * offer actions the user's role in the viewed team does not permit. It corrects
 * itself on the next request, which is what made it easy to miss.
 *
 * Largely unreachable until recently: the switcher listed only owned teams and
 * switching anywhere else was refused. Widening it to teams a user belongs to
 * made cross-team navigation an ordinary path.
 */
class PermissionsFollowTenantTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_permission_team_matches_the_tenant_being_viewed(): void
    {
        [$user, $otherTeamId] = $this->userInTwoTeams();

        $this->actingAs($user)->get('/app/'.$otherTeamId)->assertSuccessful();

        $this->assertSame(
            $otherTeamId,
            app(PermissionRegistrar::class)->getPermissionsTeamId(),
            'Permissions resolved against the team the user arrived on, not the one in the URL.',
        );
    }

    public function test_the_permission_team_matches_when_viewing_your_own_team(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $this->actingAs($user)->get('/app/'.$user->current_team_id)->assertSuccessful();

        $this->assertSame(
            $user->current_team_id,
            app(PermissionRegistrar::class)->getPermissionsTeamId(),
        );
    }

    /**
     * PermissionRegistrar is a singleton and production runs Octane, so state
     * survives between requests in a worker. Routes inside the panel's auth
     * group but outside its tenant group — team creation, logout, profile,
     * email verification — would otherwise inherit whichever team the previous
     * request left set. The middleware is registered in both groups for this
     * reason, not by accident.
     */
    public function test_a_route_without_a_tenant_still_resets_the_permission_team(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        // Stand in for a previous request in the same Octane worker.
        app(PermissionRegistrar::class)->setPermissionsTeamId(999999);

        $this->actingAs($user)->get('/app/new')->assertSuccessful();

        $this->assertSame(
            $user->current_team_id,
            app(PermissionRegistrar::class)->getPermissionsTeamId(),
            'A tenant-less route left another request\'s permission team in place.',
        );
    }

    /**
     * The tenant scope used to read the stored current team, which agreed with
     * the URL only because the SwitchTeam listener had already run. That is an
     * unnamed dependency between a global scope and an event listener: remove
     * or reorder the listener and scoping silently reverts to a stale team with
     * nothing failing. Reading the active tenant removes the coupling.
     */
    public function test_the_tenant_scope_reads_the_active_tenant_not_the_stored_column(): void
    {
        [$user, $otherTeamId] = $this->userInTwoTeams();

        $this->actingAs($user)->get('/app/'.$otherTeamId)->assertSuccessful();

        // Deliberately desynchronise the stored column from the active tenant —
        // the state the listener would otherwise always paper over. The
        // authenticated instance has to be re-resolved, or it keeps serving a
        // cached currentTeam relation and the test passes without proving
        // anything.
        $ownTeamId = $user->ownedTeams()->first()->id;
        $user->forceFill(['current_team_id' => $ownTeamId])->save();
        $this->actingAs($user->fresh());

        $this->assertNotSame($ownTeamId, $otherTeamId, 'Fixture is degenerate.');

        Filament::setTenant($user->allTeams()->firstWhere('id', $otherTeamId), isQuiet: true);

        $this->assertSame(
            $otherTeamId,
            $this->resolvedTenantId(),
            'The scope followed the stored column rather than the tenant being viewed.',
        );
    }

    public function test_without_a_panel_context_the_scope_falls_back_to_the_stored_team(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Filament::setTenant(null, isQuiet: true);

        // Console commands and queued jobs have no tenant set; they must still
        // scope rather than silently falling through to every team's rows.
        $this->assertSame($user->current_team_id, $this->resolvedTenantId());
    }

    /**
     * @return array{0: User, 1: int}
     */
    private function userInTwoTeams(): array
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $user = User::factory()->withPersonalTeam()->create();

        $owner->currentTeam->users()->attach($user, ['role' => 'editor']);

        return [$user->fresh(), $owner->current_team_id];
    }

    private function resolvedTenantId(): ?int
    {
        $method = new \ReflectionMethod(Person::class, 'getTenantId');
        $method->setAccessible(true);

        return $method->invoke(null);
    }
}
