<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\App\Resources\PersonResource\Pages\EditPerson;
use App\Filament\App\Resources\PersonResource\RelationManagers\SourcesRelationManager;
use App\Models\Person;
use App\Models\Source;
use App\Models\SourceRef;
use App\Models\User;
use App\Services\CompletenessService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
// PHPUnit 13 dropped the @dataProvider docblock; only the attribute is read.
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Person↔Source evidence links (GEDCOM SOUR, group 'indi').
 *
 * The importer only ever writes the fine-grained groups (indi_name, indi_even,
 * indi_asso, indi_lds), so nothing had ever written group='indi' and the person
 * half of CompletenessService::sourceCompleteness() structurally returned 0.
 * These cover the UI that writes it and the report that reads it.
 */
class PersonSourceLinkTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam);

        // Relation managers gate their create/edit actions on the resource policy —
        // SourceRefPolicy::create() wants Shield's `create_source::ref` permission —
        // and Filament HIDES an unauthorized action rather than failing it, so without
        // this the create action is simply absent and the test reads as a wiring bug.
        // Shield grants super_admin a Gate::before short-circuit in production; that
        // hook is not active in the test harness, so replicate it. These tests cover
        // the source-link wiring, not Shield authorization.
        Gate::before(fn () => true);

        return $user;
    }

    public function test_creating_through_the_relation_stamps_group_gid_and_team(): void
    {
        $user = $this->actingUser();
        $person = Person::factory()->create();
        $source = Source::factory()->create();

        $ref = $person->sourceRefs()->create([
            'sour_id' => $source->id,
            'page' => 'p. 42',
            'quay' => '3',
            'text' => 'Entry for the subject.',
        ]);

        // Read the row back past the tenant scope: a null team_id would still be
        // readable through $ref, but invisible to the (scoped) report.
        $stored = SourceRef::withoutGlobalScopes()->findOrFail($ref->id);

        $this->assertSame(SourceRef::GROUP_INDI, $stored->group);
        $this->assertSame($person->id, $stored->gid);
        $this->assertSame($user->currentTeam->id, $stored->team_id);
    }

    public function test_source_and_person_relations_resolve(): void
    {
        $this->actingUser();
        $person = Person::factory()->create();
        $source = Source::factory()->create();

        $ref = $person->sourceRefs()->create(['sour_id' => $source->id]);

        $this->assertTrue($ref->source->is($source));
        $this->assertTrue($ref->person->is($person));
    }

    public function test_source_refs_excludes_other_groups_for_the_same_gid(): void
    {
        $this->actingUser();
        $person = Person::factory()->create();
        $source = Source::factory()->create();

        $indi = $person->sourceRefs()->create(['sour_id' => $source->id]);

        // Same gid, different group: an event-level ref the importer would write.
        // gid collides by design — (group, gid) is the pseudo-polymorphic key.
        SourceRef::create([
            'group' => SourceRef::GROUP_INDI_EVEN,
            'gid' => $person->id,
            'sour_id' => $source->id,
        ]);

        $this->assertSame([$indi->id], $person->sourceRefs()->pluck('id')->all());
    }

    public function test_source_people_relation_returns_only_indi_refs(): void
    {
        $this->actingUser();
        $person = Person::factory()->create();
        $other = Person::factory()->create();
        $source = Source::factory()->create();

        $person->sourceRefs()->create(['sour_id' => $source->id]);
        SourceRef::create([
            'group' => SourceRef::GROUP_INDI_EVEN,
            'gid' => $other->id,
            'sour_id' => $source->id,
        ]);

        $this->assertSame([$person->id], $source->people()->pluck('people.id')->all());
    }

    /**
     * The regression this whole feature exists for: person coverage was
     * structurally 0% because no writer ever produced group='indi'.
     */
    public function test_source_completeness_person_coverage_goes_from_zero_to_nonzero(): void
    {
        $this->actingUser();
        $person = Person::factory()->create();
        $source = Source::factory()->create();

        // An importer-written ref against the same person does NOT count: the
        // report counts group='indi' only. This is the state the report was stuck in.
        SourceRef::create([
            'group' => SourceRef::GROUP_INDI_NAME,
            'gid' => $person->id,
            'sour_id' => $source->id,
        ]);

        $before = (new CompletenessService)->sourceCompleteness();
        $this->assertSame(1, $before['persons']['total']);
        $this->assertSame(0, $before['persons']['with_source']);
        $this->assertSame(0.0, $before['persons']['percentage']);

        $person->sourceRefs()->create(['sour_id' => $source->id]);

        $after = (new CompletenessService)->sourceCompleteness();
        $this->assertSame(1, $after['persons']['with_source']);
        $this->assertSame(100.0, $after['persons']['percentage']);
    }

    public function test_source_completeness_ignores_other_teams_refs(): void
    {
        $this->actingUser();
        $person = Person::factory()->create();

        $otherTeam = User::factory()->withPersonalTeam()->create()->currentTeam;

        // A ref owned by another team must not count the person as sourced — the
        // subquery has to stay tenant-scoped. team_id is not fillable, so assign it
        // directly: via create() it would be dropped and then stamped with the
        // acting team, quietly turning this into a test of nothing.
        $ref = new SourceRef([
            'group' => SourceRef::GROUP_INDI,
            'gid' => $person->id,
            'sour_id' => Source::factory()->create()->id,
        ]);
        $ref->team_id = $otherTeam->id;
        $ref->save();

        // fresh() would re-apply the tenant scope and find nothing.
        $this->assertSame($otherTeam->id, SourceRef::withoutGlobalScopes()->findOrFail($ref->id)->team_id);

        $this->assertSame(0, (new CompletenessService)->sourceCompleteness()['persons']['with_source']);
    }

    #[DataProvider('qualityLabelProvider')]
    public function test_quality_label(?string $quay, string $expected): void
    {
        $this->assertSame($expected, (new SourceRef(['quay' => $quay]))->qualityLabel());
    }

    /** @return array<string, array{0: ?string, 1: string}> */
    public static function qualityLabelProvider(): array
    {
        return [
            'unreliable' => ['0', 'Unreliable'],
            'questionable' => ['1', 'Questionable'],
            'secondary' => ['2', 'Secondary evidence'],
            'primary' => ['3', 'Primary evidence'],
            'null' => [null, 'Unrated'],
            'empty' => ['', 'Unrated'],
            // Imports carry free text in QUAY; it falls through to the raw value.
            'free text' => ['probably reliable', 'probably reliable'],
        ];
    }

    public function test_sources_relation_manager_mounts(): void
    {
        $this->actingUser();
        $person = Person::factory()->create();

        Livewire::test(SourcesRelationManager::class, [
            'ownerRecord' => $person,
            'pageClass' => EditPerson::class,
        ])->assertOk();
    }

    public function test_sources_relation_manager_creates_an_indi_ref(): void
    {
        $user = $this->actingUser();
        $person = Person::factory()->create();
        $source = Source::factory()->create();

        Livewire::test(SourcesRelationManager::class, [
            'ownerRecord' => $person,
            'pageClass' => EditPerson::class,
        ])
            ->callTableAction('create', data: [
                'sour_id' => $source->id,
                'page' => 'p. 7',
                'quay' => '2',
                'text' => 'Transcribed entry.',
            ])
            ->assertHasNoActionErrors();

        $ref = SourceRef::withoutGlobalScopes()->firstOrFail();

        $this->assertSame(SourceRef::GROUP_INDI, $ref->group);
        $this->assertSame($person->id, $ref->gid);
        $this->assertSame($source->id, $ref->sour_id);
        $this->assertSame('p. 7', $ref->page);
        $this->assertSame($user->currentTeam->id, $ref->team_id);
    }

    public function test_sources_relation_manager_requires_a_source(): void
    {
        $this->actingUser();
        $person = Person::factory()->create();

        Livewire::test(SourcesRelationManager::class, [
            'ownerRecord' => $person,
            'pageClass' => EditPerson::class,
        ])
            ->callTableAction('create', data: ['page' => 'p. 7'])
            ->assertHasActionErrors(['sour_id' => ['required']]);

        $this->assertSame(0, SourceRef::withoutGlobalScopes()->count());
    }
}
