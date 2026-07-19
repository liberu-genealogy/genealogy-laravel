<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Filament\App\Resources\PersonResource;
use App\Models\Person;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * The migration that stops existing collaborators being locked out.
 *
 * It runs once, against real data, on an upgrade, and on a fresh database it is
 * a no-op — so nothing else in this suite exercises it. Enforcing the tiers
 * turned a null tier from harmless into a total refusal, which would have taken
 * access away from every membership row created before tiers were read.
 */
class BackfillCollaborationTierTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_membership_with_no_tier_is_given_the_read_only_one(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();
        $owner->currentTeam->users()->attach($member, ['role' => null]);

        $this->runMigration();

        $this->assertSame(
            'viewer',
            DB::table('team_user')->where('user_id', $member->id)->value('role'),
        );
    }

    /**
     * The point of the migration rather than its mechanism: the member can
     * still open the panel afterwards.
     */
    public function test_a_backfilled_member_can_read_again(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();
        $owner->currentTeam->users()->attach($member, ['role' => null]);

        $this->actingAs($member->fresh());
        Filament::setTenant($owner->currentTeam->fresh(), isQuiet: true);
        $this->assertFalse(PersonResource::canViewAny(), 'Fixture is degenerate: they could already read.');

        $this->runMigration();

        $this->actingAs($member->fresh());
        Filament::setTenant($owner->currentTeam->fresh(), isQuiet: true);

        $this->assertTrue(PersonResource::canViewAny(), 'A backfilled member still could not read.');
        $this->assertFalse(PersonResource::canDelete($this->person()), 'The backfill granted more than reading.');
    }

    public function test_it_does_not_disturb_a_tier_someone_chose(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $editor = User::factory()->withPersonalTeam()->create();
        $owner->currentTeam->users()->attach($editor, ['role' => 'editor']);

        $this->runMigration();

        $this->assertSame(
            'editor',
            DB::table('team_user')->where('user_id', $editor->id)->value('role'),
            'The backfill overwrote a tier that had been set deliberately.',
        );
    }

    private function person(): Person
    {
        return Person::factory()->make(['team_id' => Filament::getTenant()?->getKey()]);
    }

    private function runMigration(): void
    {
        (require database_path('migrations/2026_07_19_140000_backfill_collaboration_tier.php'))->up();
    }
}
