<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AssociationType;
use App\Filament\App\Resources\PersonResource\Pages\EditPerson;
use App\Filament\App\Resources\PersonResource\RelationManagers\AssociationsRelationManager;
use App\Models\Person;
use App\Models\PersonAsso;
use App\Models\User;
use App\Services\PersonMergeService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * GEDCOM ASSO surfaced in the UI: step-parents, guardians, godparents and
 * witnesses — the links no family record expresses.
 *
 * person_asso is tenant-scoped via BelongsToTenant, which keys off
 * auth()->user()->currentTeam, so every test here must act as a user with a team
 * or the global scope silently no-ops and proves nothing.
 */
class PersonAssociationsTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam);

        // Relation managers gate their create/edit actions on the resource policy —
        // PersonAssoPolicy::create() wants Shield's `create_person::asso` permission —
        // and Filament HIDES an unauthorized action rather than failing it, so without
        // this the create action is simply absent and the test reads as a wiring bug.
        // Shield grants super_admin a Gate::before short-circuit in production; that
        // hook is not active in the test harness, so replicate it. These tests cover
        // the association wiring, not Shield authorization.
        Gate::before(fn () => true);

        return $user;
    }

    public function test_creating_via_the_relation_stamps_group_and_gid(): void
    {
        $user = $this->actingUser();
        $person = Person::factory()->create();
        $guardian = Person::factory()->create();

        $association = $person->associations()->create([
            'indi' => (string) $guardian->getKey(),
            'rela' => AssociationType::GUARDIAN->value,
            'import_confirm' => 1,
        ]);

        // withAttributes() stamps `group`; the HasMany stamps `gid`.
        $this->assertSame(PersonAsso::GROUP_INDI, $association->group);
        $this->assertSame($person->getKey(), $association->gid);
        $this->assertSame($user->currentTeam->id, $association->team_id);

        $this->assertTrue($person->associations()->get()->contains($association));
    }

    public function test_associate_resolves_to_the_right_person(): void
    {
        $this->actingUser();
        $person = Person::factory()->create();
        $godparent = Person::factory()->create();

        $association = $person->associations()->create([
            'indi' => (string) $godparent->getKey(),
            'rela' => AssociationType::GODPARENT->value,
            'import_confirm' => 1,
        ]);

        $this->assertTrue($association->isResolved());
        $this->assertSame($godparent->getKey(), $association->associate->getKey());
        $this->assertSame($person->getKey(), $association->person->getKey());
    }

    public function test_unresolved_xref_resolves_to_null_without_throwing(): void
    {
        $this->actingUser();
        $person = Person::factory()->create();

        // What the importer writes before its resolution pass runs.
        $association = $person->associations()->create([
            'indi' => '@I5@',
            'rela' => AssociationType::WITNESS->value,
            'import_confirm' => 0,
        ]);

        $this->assertFalse($association->isResolved());
        $this->assertNull($association->associate);
    }

    public function test_associated_with_finds_the_association_from_the_other_side(): void
    {
        $this->actingUser();
        $ward = Person::factory()->create();
        $guardian = Person::factory()->create();

        $association = $ward->associations()->create([
            'indi' => (string) $guardian->getKey(),
            'rela' => AssociationType::GUARDIAN->value,
            'import_confirm' => 1,
        ]);

        // GEDCOM stores the ASSO one way only: the guardian has no row of their own.
        $incoming = $guardian->associatedWith()->get();

        $this->assertCount(1, $incoming);
        $this->assertSame($association->id, $incoming->first()->id);
        $this->assertSame(AssociationType::WARD, $incoming->first()->type()->inverse());
    }

    public function test_label_for_falls_back_to_raw_free_text(): void
    {
        $this->assertSame('Step-parent', AssociationType::labelFor('step-parent'));
        // RELA is free text in the spec, so imports carry values outside our enum.
        $this->assertSame('Godfather at baptism', AssociationType::labelFor('Godfather at baptism'));
        $this->assertSame('Unknown', AssociationType::labelFor(null));
    }

    public function test_merge_repoints_associations_in_both_directions(): void
    {
        $this->actingUser();
        $primary = Person::factory()->create();
        $duplicate = Person::factory()->create();
        $other = Person::factory()->create();

        // The duplicate as subject...
        $asSubject = $duplicate->associations()->create([
            'indi' => (string) $other->getKey(),
            'rela' => AssociationType::GUARDIAN->value,
            'import_confirm' => 1,
        ]);

        // ...and as the associate of someone else.
        $asAssociate = $other->associations()->create([
            'indi' => (string) $duplicate->getKey(),
            'rela' => AssociationType::GODPARENT->value,
            'import_confirm' => 1,
        ]);

        app(PersonMergeService::class)->merge($primary, $duplicate);

        $this->assertSame($primary->getKey(), $asSubject->fresh()->gid);
        $this->assertSame((string) $primary->getKey(), $asAssociate->fresh()->indi);
    }

    public function test_relation_manager_mounts(): void
    {
        $this->actingUser();
        $person = Person::factory()->create();

        Livewire::test(AssociationsRelationManager::class, [
            'ownerRecord' => $person,
            'pageClass' => EditPerson::class,
        ])->assertOk();
    }

    public function test_relation_manager_creates_a_resolved_association(): void
    {
        $this->actingUser();
        $person = Person::factory()->create();
        $stepParent = Person::factory()->create();

        Livewire::test(AssociationsRelationManager::class, [
            'ownerRecord' => $person,
            'pageClass' => EditPerson::class,
        ])
            ->callTableAction('create', data: [
                'indi' => (string) $stepParent->getKey(),
                'rela' => AssociationType::STEP_PARENT->value,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('person_asso', [
            'group' => PersonAsso::GROUP_INDI,
            'gid' => $person->getKey(),
            'indi' => (string) $stepParent->getKey(),
            'rela' => AssociationType::STEP_PARENT->value,
            // The UI never creates rows awaiting xref resolution.
            'import_confirm' => 1,
        ]);
    }
}
