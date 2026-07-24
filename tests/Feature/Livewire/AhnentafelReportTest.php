<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\AhnentafelReport;
use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class AhnentafelReportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
        $team = $this->user->ownedTeams()->first();
        if ($team) {
            $this->user->forceFill(['current_team_id' => $team->id])->save();
        }
        $this->actingAs($this->user);
    }

    /**
     * Drive the report the way the UI does: fill the Filament form's person
     * state, then invoke the generate action's handler.
     */
    private function generateFor(int|string|null $personId): Testable
    {
        return Livewire::test(AhnentafelReport::class)
            ->set('data.person', $personId)
            ->call('generateReport');
    }

    public function test_component_renders(): void
    {
        Livewire::test(AhnentafelReport::class)->assertOk();
    }

    public function test_initial_state_has_no_report_data(): void
    {
        Livewire::test(AhnentafelReport::class)
            ->assertSet('selectedPersonId', null)
            ->assertSet('reportData', []);
    }

    public function test_generate_report_with_valid_person(): void
    {
        $person = Person::factory()->create();

        $this->generateFor($person->id)
            ->assertSet('selectedPersonId', $person->id);
    }

    public function test_generate_report_with_nonexistent_person(): void
    {
        $this->generateFor(99999)
            ->assertSet('reportData', []);
    }

    public function test_report_carries_the_gedcom_birth_and_death_place(): void
    {
        // The report read $person->birth_place / ->death_place, which are not
        // columns (GEDCOM import writes birthday_plac / deathday_plac), so the
        // place always came back empty.
        $person = Person::factory()->create([
            'birthday_plac' => 'Manchester, England',
            'deathday_plac' => 'Leeds, England',
        ]);

        $data = $this->generateFor($person->id)->get('reportData');

        $this->assertSame('Manchester, England', $data[1]['birth_place']);
        $this->assertSame('Leeds, England', $data[1]['death_place']);
    }

    public function test_ahnentafel_numbering_subject_father_mother(): void
    {
        // subject = 1, father = 2n, mother = 2n+1.
        $father = Person::factory()->create();
        $mother = Person::factory()->create();
        $family = Family::factory()->create([
            'husband_id' => $father->id,
            'wife_id' => $mother->id,
        ]);
        $subject = Person::factory()->create(['child_in_family_id' => $family->id]);

        $data = $this->generateFor($subject->id)->get('reportData');

        $this->assertSame($subject->id, $data[1]['person_id']);
        $this->assertSame($father->id, $data[2]['person_id']);
        $this->assertSame($mother->id, $data[3]['person_id']);
    }

    public function test_person_with_no_ancestors_yields_only_the_subject(): void
    {
        $person = Person::factory()->create();

        $data = $this->generateFor($person->id)->get('reportData');

        $this->assertCount(1, $data);
        $this->assertArrayHasKey(1, $data);
    }

    public function test_pedigree_collapse_places_one_person_at_several_numbers(): void
    {
        // Both parents descend from the SAME grandparents (a shared ancestor
        // reached by two paths). Ahnentafel numbers a tree POSITION, not a
        // person, so the grandparents legitimately appear twice — must NOT be
        // deduped by person id.
        $gf = Person::factory()->create();
        $gm = Person::factory()->create();
        $gpFamily = Family::factory()->create(['husband_id' => $gf->id, 'wife_id' => $gm->id]);

        $father = Person::factory()->create(['child_in_family_id' => $gpFamily->id]);
        $mother = Person::factory()->create(['child_in_family_id' => $gpFamily->id]);
        $parents = Family::factory()->create(['husband_id' => $father->id, 'wife_id' => $mother->id]);
        $subject = Person::factory()->create(['child_in_family_id' => $parents->id]);

        $data = $this->generateFor($subject->id)->get('reportData');

        // #4 = father's father, #6 = mother's father — same real person.
        $this->assertSame($gf->id, $data[4]['person_id']);
        $this->assertSame($gf->id, $data[6]['person_id']);
        // #5 = father's mother, #7 = mother's mother — same real person.
        $this->assertSame($gm->id, $data[5]['person_id']);
        $this->assertSame($gm->id, $data[7]['person_id']);
        $this->assertCount(7, $data);
    }
}
