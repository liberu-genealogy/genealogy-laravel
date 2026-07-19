<?php

declare(strict_types=1);

namespace Tests\Filament\Admin;

use App\Filament\Admin\Resources\DnaMatchingResource\Pages\ListDnaMatchings;
use App\Filament\Admin\Resources\ImportJobResource\Pages\ListImportJobs;
use App\Filament\Admin\Resources\TreeResource\Pages\ListTrees;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * The admin panel monitors trees, imports and DNA across every team (SCOPE §18).
 * Each of these resources strips BelongsToTenant's global scope in
 * getEloquentQuery() for cross-team visibility and moves layout/columns to the
 * Filament v5 namespaces — both only fail when the page schema is built, i.e. on
 * mount. This mounts each list page under the admin panel so a regression (dead
 * namespace, bad relation column, removed method) fails loudly.
 */
class AdminMonitoringTest extends TestCase
{
    use RefreshDatabase;

    private function actingAdmin(): User
    {
        // A personal-team user is authenticated *with* a current team, so
        // BelongsToTenant's scope is live — exactly the condition the resources'
        // withoutGlobalScopes() has to override for cross-team monitoring.
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        // The admin panel is authorization-gated (User::canAccessPanel requires
        // super_admin or admin); grant super_admin so the panel allows the mount.
        //
        // This used to say the resource policies were satisfied by a Shield
        // Gate::before short-circuit. They are not: that hook only exists when
        // super_admin.define_via_gate is true, and it defaults to false and is
        // not overridden here. The policies are satisfied by the explicit
        // Gate::before below, which this test installs itself.
        //
        // Roles are team-scoped now, which this had to be updated for. The role
        // is created team-less, as the seeder creates it — that is what the
        // admin gate tests for. The grant of it still needs a team, so the
        // user's own is named; without one the assignment writes a null into a
        // primary key column and fails outright.
        $role = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);
        app(PermissionRegistrar::class)->setPermissionsTeamId($user->current_team_id);
        $user->assignRole($role);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // canAccessPanel('admin') passes on the role above, but the admin panel
        // also runs FilamentShield resource policies (the app panel doesn't).
        // In production super_admin bypasses them via Shield's Gate::before; that
        // hook isn't active in the test harness, so replicate it here. This test
        // verifies the resource SCHEMA builds on mount, not Shield authz.
        Gate::before(fn () => true);

        Filament::setCurrentPanel('admin');

        return $user;
    }

    public function test_import_jobs_list_page_mounts(): void
    {
        $this->actingAdmin();

        Livewire::test(ListImportJobs::class)->assertOk();
    }

    public function test_trees_list_page_mounts(): void
    {
        $this->actingAdmin();

        Livewire::test(ListTrees::class)->assertOk();
    }

    public function test_dna_matches_list_page_mounts(): void
    {
        $this->actingAdmin();

        Livewire::test(ListDnaMatchings::class)->assertOk();
    }
}
