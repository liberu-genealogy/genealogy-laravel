<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Filament\App\Resources\PersonResource\RelationManagers\PhotosRelationManager;
use App\Models\Person;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * A collaborator's tier has to govern what hangs off a record as well as the
 * record itself.
 *
 * Enforcing the tiers on resources left this open. Filament sends a relation
 * manager's authorisation to the related model's policy unless the manager
 * names a related resource, and none of the six here did — so several resolved
 * against a model with no policy at all, which allows. A viewer could
 * legitimately open a person, because they hold read, and then delete every
 * photo, source and association attached to them.
 *
 * The parent resource being authorised is exactly what makes this reachable:
 * you have to be let in before you can act on what is inside.
 */
class RelationManagerTierEnforcementTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_viewer_may_read_but_not_change_what_hangs_off_a_record(): void
    {
        $manager = $this->managerFor('viewer');

        $this->assertTrue($manager->getAuthorizationResponse('view')->allowed(), 'A viewer could not read.');
        $this->assertFalse($manager->getAuthorizationResponse('create')->allowed(), 'A viewer could attach records.');
        $this->assertFalse($manager->getAuthorizationResponse('update')->allowed(), 'A viewer could edit them.');
        $this->assertFalse($manager->getAuthorizationResponse('delete')->allowed(), 'A viewer could delete them.');
    }

    public function test_a_contributor_may_add_but_not_delete(): void
    {
        $manager = $this->managerFor('contributor');

        $this->assertTrue($manager->getAuthorizationResponse('create')->allowed());
        $this->assertTrue($manager->getAuthorizationResponse('update')->allowed());
        $this->assertFalse($manager->getAuthorizationResponse('delete')->allowed());
    }

    public function test_an_editor_may_delete(): void
    {
        $this->assertTrue($this->managerFor('editor')->getAuthorizationResponse('delete')->allowed());
    }

    /**
     * Detaching is how you remove a record from a relationship without deleting
     * it, and dissociating is the same for a belongs-to. Both destroy the link
     * a researcher recorded, so both belong with delete rather than with the
     * update tier — and neither has a can* hook of its own, so an unnamed
     * action falling through to something permissive is the failure mode.
     */
    public function test_a_contributor_cannot_detach_or_dissociate(): void
    {
        $manager = $this->managerFor('contributor');

        foreach (['detach', 'detachAny', 'dissociate', 'dissociateAny'] as $action) {
            $this->assertFalse(
                $manager->getAuthorizationResponse($action)->allowed(),
                "A contributor was allowed to {$action}.",
            );
        }
    }

    public function test_an_unrecognised_action_is_refused(): void
    {
        $this->assertFalse(
            $this->managerFor('editor')->getAuthorizationResponse('somethingNobodyHasWrittenYet')->allowed(),
            'An action nobody anticipated was allowed rather than refused.',
        );
    }

    public function test_the_owner_may_do_everything(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $this->actingAs($owner);
        Filament::setTenant($owner->currentTeam, isQuiet: true);

        $manager = new PhotosRelationManager;

        $this->assertTrue($manager->getAuthorizationResponse('delete')->allowed());
    }

    public function test_without_a_tenant_nothing_is_authorised(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant(null, isQuiet: true);

        $this->assertFalse((new PhotosRelationManager)->getAuthorizationResponse('view')->allowed());
    }

    private function managerFor(string $tier): PhotosRelationManager
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();
        $owner->currentTeam->users()->attach($member, ['role' => $tier]);

        $this->actingAs($member->fresh());
        Filament::setTenant($owner->currentTeam->fresh(), isQuiet: true);

        Person::factory()->create(['team_id' => $owner->current_team_id]);

        return new PhotosRelationManager;
    }
}
