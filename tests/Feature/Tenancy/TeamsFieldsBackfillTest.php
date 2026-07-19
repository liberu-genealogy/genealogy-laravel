<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Tests\TestCase;

/**
 * The add_teams_fields migration backfills existing role assignments.
 *
 * That code runs exactly once, against real data, on an upgrade — the path
 * least likely to be exercised before it matters and most expensive to get
 * wrong. On a fresh database it never runs at all, because
 * create_permission_tables now creates the columns itself and the migration
 * skips every branch. So nothing else in this suite touches it.
 *
 * These tests put the pivot back into its pre-migration shape, populate it, and
 * run the migration for real.
 *
 * Note what is NOT covered: the primary key rebuild is dialect-specific, and
 * SQLite reaches it by rewriting the whole table where MariaDB alters in place.
 * The MariaDB shape was verified by hand against the dev database. This covers
 * the backfill decision, not the DDL.
 */
class TeamsFieldsBackfillTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_existing_assignment_is_scoped_to_the_holders_current_team(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $roleId = $this->givenAPreMigrationAssignment($user);

        $this->runMigration();

        $this->assertSame(
            $user->current_team_id,
            DB::table('model_has_roles')->where('role_id', $roleId)->value('team_id'),
            'The grant was not scoped to the team its holder was working in.',
        );
    }

    /**
     * The stub shipped by the permission package defaults this column to 1,
     * which would write every pre-existing grant to whichever team holds that
     * id — including, as here, a team the holder is not a member of and one
     * that need not exist at all. Refusing is the point.
     */
    public function test_an_assignment_that_cannot_be_attributed_to_a_team_stops_the_migration(): void
    {
        $user = User::factory()->create(); // No team.
        $this->assertNull($user->current_team_id, 'Fixture is degenerate: the user has a team.');

        $this->givenAPreMigrationAssignment($user);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/Cannot scope 1 row\(s\) in model_has_roles/');

        $this->runMigration();
    }

    /**
     * current_team_id outlives membership: removeUser() and a bare detach()
     * both leave it pointing at a team the user can no longer open. Scoping a
     * grant there would produce a row nothing displays and nobody can revoke,
     * so it is refused rather than written.
     */
    public function test_a_grant_whose_holder_has_left_their_current_team_stops_the_migration(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $user = User::factory()->withPersonalTeam()->create();

        $owner->currentTeam->users()->attach($user, ['role' => 'editor']);
        $user->forceFill(['current_team_id' => $owner->current_team_id])->save();
        $owner->currentTeam->users()->detach($user);

        $this->givenAPreMigrationAssignment($user->fresh());

        $this->expectException(RuntimeException::class);

        $this->runMigration();
    }

    /**
     * A team owner is not a row in team_user — Jetstream tracks ownership on
     * the team itself. Testing membership alone would reject every personal
     * team, which is most of them, and abort the upgrade for nearly everyone.
     */
    public function test_a_team_owner_counts_as_able_to_reach_their_own_team(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();

        $this->assertDatabaseMissing('team_user', ['user_id' => $owner->id, 'team_id' => $owner->current_team_id]);

        $roleId = $this->givenAPreMigrationAssignment($owner);

        $this->runMigration();

        $this->assertSame(
            $owner->current_team_id,
            DB::table('model_has_roles')->where('role_id', $roleId)->value('team_id'),
            'An owner was treated as unable to reach the team they own.',
        );
    }

    /**
     * Put model_has_roles back the way it was before the teams column existed,
     * and give it one role assignment to carry across.
     */
    private function givenAPreMigrationAssignment(User $user): int
    {
        // Recreated rather than altered: team_id is part of the primary key,
        // and SQLite can neither drop a key column nor redefine a primary key
        // in place.
        Schema::drop('model_has_roles');
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        $roleId = DB::table('roles')->insertGetId([
            'name' => 'legacy_admin',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('model_has_roles')->insert([
            'role_id' => $roleId,
            'model_type' => User::class,
            'model_id' => $user->id,
        ]);

        return $roleId;
    }

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_07_19_130000_add_teams_fields.php');

        $migration->up();
    }
}
