<?php

declare(strict_types=1);

namespace Tests\Feature\Modules;

use App\Models\Person;
use App\Models\User;
use App\Modules\ModuleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Uninstalling a module must not destroy its data.
 *
 * BaseModule::uninstall() used to call a rollbackMigrations() with an empty
 * body, so an administrator got a success result and a ModuleUninstalled event
 * while every table and row stayed put. It was accidentally safe and actively
 * misleading — the signature advertised a rollback that never ran.
 *
 * Be clear about what this test is: it passes against the old code too, and
 * deliberately so. Removing an empty method changes no behaviour — what changed
 * is that the signature no longer promises one. This is a characterisation
 * test, pinning the decision so that nobody later "completes" the rollback and
 * turns a mis-clicked uninstall into unrecoverable data loss. It guards a
 * decision, it does not catch a regression.
 */
class UninstallKeepsDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_uninstalling_a_module_leaves_its_data_intact(): void
    {
        Event::fake();

        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Person::factory()->count(3)->create(['team_id' => $user->current_team_id]);

        $module = app(ModuleManager::class)->get('Person');
        $this->assertNotNull($module, 'The Person module should be discoverable.');

        $module->uninstall();

        $this->assertSame(3, Person::withoutGlobalScopes()->count());
        $this->assertTrue(Schema::hasTable('people'));
    }

    public function test_uninstalling_reports_the_module_as_disabled(): void
    {
        Event::fake();

        $module = app(ModuleManager::class)->get('Person');
        $this->assertNotNull($module);

        $module->enable();
        $this->assertTrue($module->isEnabled());

        $module->uninstall();

        $this->assertFalse($module->isEnabled());
    }
}
