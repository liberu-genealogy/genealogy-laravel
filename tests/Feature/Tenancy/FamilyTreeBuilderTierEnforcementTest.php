<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Livewire\FamilyTreeBuilder;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * The tree builder creates, moves and deletes the people a team records, and it
 * did so with no authorisation at all.
 *
 * It is a Livewire component on a plain web route, not a panel page, so none of
 * the resource-level tier enforcement reached it and no tenant middleware runs
 * in front of it. Its methods are addressable over the wire regardless of what
 * the interface shows, so a viewer — invited to look at one family's research —
 * could add people to it, drag the whole tree around, and delete anyone in it.
 *
 * The records are the team's, so the tier is what governs them: a viewer reads,
 * a contributor adds and edits, an editor deletes. The component resolves its
 * team from the user's current team, since there is no panel tenant on this
 * route.
 */
class FamilyTreeBuilderTierEnforcementTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_viewer_cannot_add_a_person(): void
    {
        $this->actingAsMember('viewer');

        Livewire::test(FamilyTreeBuilder::class)
            ->call('addPerson', ['givn' => 'Ada', 'surn' => 'Lovelace'])
            ->assertForbidden();

        $this->assertSame(0, Person::count(), 'A viewer created a person in the tree.');
    }

    public function test_a_viewer_cannot_move_a_person(): void
    {
        $team = $this->actingAsMember('viewer');
        $person = Person::factory()->create(['team_id' => $team, 'tree_position_x' => 10, 'tree_position_y' => 20]);

        Livewire::test(FamilyTreeBuilder::class)
            ->call('updatePersonPosition', $person->id, 999, 999)
            ->assertForbidden();

        $this->assertSame(10.0, (float) $person->fresh()->tree_position_x, 'A viewer moved a person in the tree.');
    }

    public function test_a_viewer_cannot_delete_a_person(): void
    {
        $team = $this->actingAsMember('viewer');
        $person = Person::factory()->create(['team_id' => $team]);

        Livewire::test(FamilyTreeBuilder::class)
            ->call('removePerson', $person->id)
            ->assertForbidden();

        // assertNotSoftDeleted, not assertNotNull: the row soft-deletes, so
        // fresh() would return it even after a successful delete, and the test
        // would pass while the person was in fact gone.
        $this->assertNotSoftDeleted($person, [], 'A viewer deleted a person from the tree.');
    }

    public function test_a_contributor_may_add_and_move_but_not_delete(): void
    {
        $team = $this->actingAsMember('contributor');

        Livewire::test(FamilyTreeBuilder::class)
            ->call('addPerson', ['givn' => 'Ada', 'surn' => 'Lovelace'])
            ->assertOk();
        $this->assertSame(1, Person::count(), 'A contributor could not add a person.');

        $person = Person::first();
        Livewire::test(FamilyTreeBuilder::class)
            ->call('updatePersonPosition', $person->id, 5, 5)
            ->assertOk();
        $this->assertSame(5.0, (float) $person->fresh()->tree_position_x);

        Livewire::test(FamilyTreeBuilder::class)
            ->call('removePerson', $person->id)
            ->assertForbidden();
        $this->assertNotSoftDeleted($person, [], 'A contributor deleted a person.');
    }

    public function test_an_editor_may_delete(): void
    {
        $team = $this->actingAsMember('editor');
        $person = Person::factory()->create(['team_id' => $team]);

        Livewire::test(FamilyTreeBuilder::class)
            ->call('removePerson', $person->id)
            ->assertOk();

        // Person soft-deletes, so the row survives; what must change is that it
        // is now trashed. fresh() returns it either way.
        $this->assertSoftDeleted($person, [], 'An editor could not delete a person.');
    }

    public function test_the_owner_may_do_everything(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $this->actingAs($owner);

        Livewire::test(FamilyTreeBuilder::class)
            ->call('addPerson', ['givn' => 'Ada', 'surn' => 'Lovelace'])
            ->assertOk();

        $this->assertSame(1, Person::count());
    }

    /**
     * @return int the team id the member is working in
     */
    private function actingAsMember(string $tier): int
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();
        $owner->currentTeam->users()->attach($member, ['role' => $tier]);

        // Their current team is the one the component acts in, since there is
        // no panel tenant on this route.
        $member->forceFill(['current_team_id' => $owner->current_team_id])->save();
        $this->actingAs($member->fresh());

        return $owner->current_team_id;
    }
}
