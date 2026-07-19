<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\UserChecklistManager;
use App\Models\Team;
use App\Models\User;
use App\Models\UserChecklist;
use App\Models\UserChecklistItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Research checklists belong to a user, not a team, and one user must not be
 * able to touch another's.
 *
 * The component loaded checklists and items straight from a request id with no
 * check that the id belonged to the acting user. The tenant scope on the
 * checklist table blocked the cross-team case, but not the cross-user case
 * within one team — and the item table is not tenant-scoped at all, so an item
 * id was reachable across both boundaries.
 *
 * Both users are deliberately on the SAME team here. On different teams the
 * tenant scope alone would refuse the checklist and the per-user fix would go
 * untested; same-team isolates the ownership check that is the actual subject.
 *
 * A refused load throws ModelNotFoundException — in production the HTTP handler
 * turns that into a 404, and the mutation never runs. The Livewire test harness
 * lets it propagate, so these catch it and assert on the outcome: the teammate's
 * record is untouched. The checklist soft-deletes, so a deleted row still
 * returns from find(); the assertions use assertNotSoftDeleted / the database,
 * never assertNotNull, which would pass on a row that had in fact been deleted.
 */
class UserChecklistManagerAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    private User $attacker;

    private Team $team;

    protected function setUp(): void
    {
        parent::setUp();

        $this->team = Team::factory()->create();
        $this->owner = User::factory()->create(['current_team_id' => $this->team->id]);
        $this->attacker = User::factory()->create(['current_team_id' => $this->team->id]);
        $this->team->users()->attach([$this->owner->id => ['role' => 'editor'], $this->attacker->id => ['role' => 'editor']]);
    }

    public function test_a_user_cannot_delete_another_users_checklist(): void
    {
        $checklist = $this->checklistFor($this->owner);

        $this->refused(fn () => Livewire::actingAs($this->attacker)
            ->test(UserChecklistManager::class)
            ->call('deleteChecklist', $checklist->id));

        $this->assertNotSoftDeleted($checklist, [], 'A user deleted another user\'s checklist.');
    }

    public function test_a_user_cannot_open_another_users_checklist_for_editing(): void
    {
        $checklist = $this->checklistFor($this->owner);

        $component = null;
        $this->refused(function () use ($checklist, &$component) {
            $component = Livewire::actingAs($this->attacker)->test(UserChecklistManager::class);
            $component->call('editChecklist', $checklist->id);
        });

        // The form must not have been populated with the other user's data.
        $component?->assertSet('selectedChecklist', null);
    }

    public function test_a_user_cannot_add_items_to_another_users_checklist(): void
    {
        $checklist = $this->checklistFor($this->owner);

        $this->refused(fn () => Livewire::actingAs($this->attacker)
            ->test(UserChecklistManager::class)
            ->call('addCustomItem', $checklist->id));

        $this->assertSame(0, $checklist->items()->count(), 'A user added an item to another user\'s checklist.');
    }

    public function test_a_user_cannot_toggle_another_users_item(): void
    {
        $item = $this->itemFor($this->checklistFor($this->owner));

        $this->refused(fn () => Livewire::actingAs($this->attacker)
            ->test(UserChecklistManager::class)
            ->call('toggleItemCompletion', $item->id));

        $this->assertFalse((bool) $item->fresh()->is_completed, 'A user toggled another user\'s item.');
    }

    public function test_a_user_cannot_edit_another_users_item(): void
    {
        $item = $this->itemFor($this->checklistFor($this->owner));

        $component = null;
        $this->refused(function () use ($item, &$component) {
            $component = Livewire::actingAs($this->attacker)->test(UserChecklistManager::class);
            $component->call('editItem', $item->id);
        });

        $component?->assertSet('selectedItem', null);
    }

    public function test_the_owner_can_still_manage_their_own_checklist(): void
    {
        $checklist = $this->checklistFor($this->owner);

        Livewire::actingAs($this->owner)
            ->test(UserChecklistManager::class)
            ->call('addCustomItem', $checklist->id)
            ->assertOk();
        $this->assertSame(1, $checklist->items()->count(), 'The owner could not add an item to their own checklist.');

        Livewire::actingAs($this->owner)
            ->test(UserChecklistManager::class)
            ->call('deleteChecklist', $checklist->id)
            ->assertOk();
        $this->assertSoftDeleted($checklist, [], 'The owner could not delete their own checklist.');
    }

    /**
     * Runs a call expected to be refused by the ownership scope, and asserts it
     * was — a load that finds nothing throws ModelNotFoundException. Anything
     * else (a successful call, a different error) fails the test.
     */
    private function refused(callable $call): void
    {
        try {
            $call();
            $this->fail('Expected the action to be refused, but it was allowed.');
        } catch (ModelNotFoundException) {
            // The scoped findOrFail matched nothing — refused, as intended.
        }
    }

    private function checklistFor(User $user): UserChecklist
    {
        $checklist = UserChecklist::create([
            'user_id' => $user->id,
            'name' => 'Private research',
            'status' => 'in_progress',
            'priority' => 'medium',
        ]);

        // The tenant scope makes an unstamped row invisible even to its owner,
        // so set the team as the real create path (running authenticated) would.
        $checklist->forceFill(['team_id' => $this->team->id])->save();

        return $checklist->fresh();
    }

    private function itemFor(UserChecklist $checklist): UserChecklistItem
    {
        return UserChecklistItem::create([
            'user_checklist_id' => $checklist->id,
            'title' => 'A task',
            'order' => 1,
            'is_completed' => false,
        ]);
    }
}
