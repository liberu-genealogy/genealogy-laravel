<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\HenryReport;
use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class HenryReportTest extends TestCase
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

    private function generateFor(int|string|null $personId, bool $modified = false): Testable
    {
        return Livewire::test(HenryReport::class)
            ->set('data.person', $personId)
            ->set('data.modified', $modified)
            ->call('generateReport');
    }

    /** Progenitor + $count children (birth-ordered), child_in_family under the progenitor. */
    private function progenitorWithChildren(int $count): Person
    {
        $progenitor = Person::factory()->create();
        $family = Family::factory()->create(['husband_id' => $progenitor->id]);

        for ($i = 1; $i <= $count; $i++) {
            Person::factory()->create([
                'child_in_family_id' => $family->id,
                'birthday' => sprintf('19%02d-01-01', $i), // 1901, 1902, … keeps birth order deterministic
            ]);
        }

        return $progenitor;
    }

    private function numbers(Testable $component): array
    {
        return collect($component->get('reportData'))->pluck('number')->all();
    }

    public function test_component_renders(): void
    {
        Livewire::test(HenryReport::class)->assertOk();
    }

    public function test_progenitor_is_1_and_children_append_birth_rank(): void
    {
        $progenitor = $this->progenitorWithChildren(2);

        $this->assertSame(['1', '11', '12'], $this->numbers($this->generateFor($progenitor->id)));
    }

    public function test_numbering_descends_multiple_generations(): void
    {
        $progenitor = Person::factory()->create();
        $f1 = Family::factory()->create(['husband_id' => $progenitor->id]);
        $child = Person::factory()->create(['child_in_family_id' => $f1->id, 'birthday' => '1900-01-01']);
        $f2 = Family::factory()->create(['husband_id' => $child->id]);
        Person::factory()->create(['child_in_family_id' => $f2->id, 'birthday' => '1930-01-01']);

        // 1 → 11 (child) → 111 (grandchild)
        $this->assertSame(['1', '11', '111'], $this->numbers($this->generateFor($progenitor->id)));
    }

    public function test_standard_henry_uses_letters_for_tenth_and_eleventh_child(): void
    {
        $progenitor = $this->progenitorWithChildren(11);

        $numbers = $this->numbers($this->generateFor($progenitor->id));

        // 1..9 → 11..19, 10th → 1X, 11th → 1A
        $this->assertContains('19', $numbers);
        $this->assertContains('1X', $numbers);
        $this->assertContains('1A', $numbers);
        $this->assertNotContains('1(10)', $numbers);
    }

    public function test_modified_henry_parenthesises_the_tenth_child(): void
    {
        $progenitor = $this->progenitorWithChildren(11);

        $numbers = $this->numbers($this->generateFor($progenitor->id, modified: true));

        $this->assertContains('1(10)', $numbers);
        $this->assertContains('1(11)', $numbers);
        $this->assertNotContains('1X', $numbers);
    }

    public function test_person_with_no_children_yields_only_the_progenitor(): void
    {
        $person = Person::factory()->create();

        $data = $this->generateFor($person->id)->get('reportData');

        $this->assertCount(1, $data);
        $this->assertSame('1', $data[0]['number']);
        $this->assertSame(0, $data[0]['depth']);
    }

    public function test_children_carry_generation_depth_for_indentation(): void
    {
        $progenitor = $this->progenitorWithChildren(1);

        $data = $this->generateFor($progenitor->id)->get('reportData');

        $this->assertSame(0, $data[0]['depth']); // progenitor
        $this->assertSame(1, $data[1]['depth']); // child
    }
}
