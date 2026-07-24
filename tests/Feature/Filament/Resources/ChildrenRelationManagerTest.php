<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\FamilyResource\Pages\EditFamily;
use App\Filament\App\Resources\FamilyResource\RelationManagers\ChildrenRelationManager;
use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * ChildrenRelationManager: children link upward via child_in_family_id, so
 * associate/dissociate set/null the FK and create makes a person straight into
 * the family. Each child's pedigree records its link type.
 */
class ChildrenRelationManagerTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam);

        // Relation-manager actions gate on the related model's policy (PersonPolicy),
        // which Filament hides/aborts without the Shield permission. Shield grants
        // super_admin a Gate::before short-circuit in production that the harness
        // lacks; replicate it so these tests cover the children wiring, not Shield.
        Gate::before(fn () => true);

        return $user;
    }

    private function livewire(Family $family): Testable
    {
        return Livewire::test(ChildrenRelationManager::class, [
            'ownerRecord' => $family,
            'pageClass' => EditFamily::class,
        ]);
    }

    public function test_relation_manager_lists_the_family_children(): void
    {
        $this->actingUser();
        $family = Family::factory()->create();
        $child = Person::factory()->create(['child_in_family_id' => $family->id]);

        $this->livewire($family)
            ->assertOk()
            ->assertCanSeeTableRecords([$child]);
    }

    public function test_existing_person_can_be_associated_as_a_child(): void
    {
        $this->actingUser();
        $family = Family::factory()->create();
        $person = Person::factory()->create(['child_in_family_id' => null]);

        $this->livewire($family)
            ->callTableAction('associate', data: ['recordId' => $person->id])
            ->assertHasNoActionErrors();

        $this->assertSame($family->id, $person->fresh()->child_in_family_id);
    }

    public function test_dissociate_removes_the_child_but_keeps_the_person(): void
    {
        $this->actingUser();
        $family = Family::factory()->create();
        $child = Person::factory()->create(['child_in_family_id' => $family->id]);

        $this->livewire($family)
            ->callTableAction('dissociate', $child)
            ->assertHasNoActionErrors();

        $this->assertNull($child->fresh()->child_in_family_id);
        $this->assertDatabaseHas('people', ['id' => $child->id]);
    }

    public function test_new_child_can_be_created_with_a_pedigree(): void
    {
        $this->actingUser();
        $family = Family::factory()->create();

        $this->livewire($family)
            ->callTableAction('create', data: ['givn' => 'New', 'surn' => 'Kid', 'pedigree' => 'adopted'])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('people', [
            'givn' => 'New',
            'surn' => 'Kid',
            'child_in_family_id' => $family->id,
            'pedigree' => 'adopted',
        ]);
    }
}
