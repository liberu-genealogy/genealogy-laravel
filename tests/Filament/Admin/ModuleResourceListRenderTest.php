<?php

declare(strict_types=1);

namespace Tests\Filament\Admin;

use App\Filament\Admin\Resources\ModuleResource\Pages\ListModules;
use App\Models\User;
use App\Modules\ModuleManager;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * ModuleResource's fake query builder cast each module to (object), but every
 * column/action closure, the `array $record` type-hint, and the info-modal
 * blade access records as arrays. So rendering a non-empty module list threw
 * "Cannot use object of type stdClass as array" on the toggle action's ->label
 * closure — a hard 500 on the admin Modules page. This mounts the list page
 * with real modules present so a regression back to object access fails loudly.
 */
class ModuleResourceListRenderTest extends TestCase
{
    use RefreshDatabase;

    private function actingAdmin(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        app(PermissionRegistrar::class)->setPermissionsTeamId($user->current_team_id);
        $user->assignRole($role);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Gate::before(fn () => true);
        Filament::setCurrentPanel('admin');

        return $user;
    }

    public function test_modules_list_page_renders_rows(): void
    {
        $this->actingAdmin();

        // Guard: the fatal only surfaces when the table has rows, so assert the
        // data source actually discovered modules before trusting assertOk().
        $modules = app(ModuleManager::class)->getAllModulesInfo();
        $this->assertNotEmpty($modules, 'No modules discovered — the render path would be untested.');

        Livewire::test(ListModules::class)
            ->assertOk()
            ->assertSee((string) $modules[array_key_first($modules)]['name']);
    }

    public function test_modules_list_page_survives_the_enabled_filter(): void
    {
        $this->actingAdmin();

        // The enabled filter routes through getModuleRecords()'s array filtering,
        // the other place the old object-vs-array mismatch fataled.
        Livewire::test(ListModules::class)
            ->filterTable('enabled', true)
            ->assertOk()
            ->filterTable('enabled', false)
            ->assertOk();
    }
}
