<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\DevillierReport;
use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class DevillierReportTest extends TestCase
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

    private function generateFor(int|string|null $personId): Testable
    {
        return Livewire::test(DevillierReport::class)
            ->set('data.person', $personId)
            ->call('generateReport');
    }

    /** Progenitor with $count birth-ordered children under one family. */
    private function progenitorWithChildren(int $count): Person
    {
        $progenitor = Person::factory()->create();
        $family = Family::factory()->create(['husband_id' => $progenitor->id]);

        for ($i = 1; $i <= $count; $i++) {
            Person::factory()->create([
                'child_in_family_id' => $family->id,
                'birthday' => sprintf('19%02d-01-01', $i),
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
        Livewire::test(DevillierReport::class)->assertOk();
    }

    public function test_progenitor_is_a1_and_children_are_b1_b2(): void
    {
        $progenitor = $this->progenitorWithChildren(2);

        // Descendants do NOT carry the root's "a1" prefix — the chain starts at "b".
        $this->assertSame(['a1', 'b1', 'b2'], $this->numbers($this->generateFor($progenitor->id)));
    }

    public function test_grandchild_reads_b2c3(): void
    {
        // Wikipedia's example: b2c3 = 3rd child of the 2nd child of the progenitor.
        $progenitor = Person::factory()->create();
        $f = Family::factory()->create(['husband_id' => $progenitor->id]);
        Person::factory()->create(['child_in_family_id' => $f->id, 'birthday' => '1900-01-01']); // b1
        $secondChild = Person::factory()->create(['child_in_family_id' => $f->id, 'birthday' => '1902-01-01']); // b2

        $f2 = Family::factory()->create(['husband_id' => $secondChild->id]);
        Person::factory()->create(['child_in_family_id' => $f2->id, 'birthday' => '1930-01-01']); // b2c1
        Person::factory()->create(['child_in_family_id' => $f2->id, 'birthday' => '1932-01-01']); // b2c2
        Person::factory()->create(['child_in_family_id' => $f2->id, 'birthday' => '1934-01-01']); // b2c3

        $this->assertContains('b2c3', $this->numbers($this->generateFor($progenitor->id)));
    }

    public function test_tenth_child_is_plain_decimal(): void
    {
        // Letters index generations, not ranks — so >9 children is native (b10).
        $progenitor = $this->progenitorWithChildren(10);

        $numbers = $this->numbers($this->generateFor($progenitor->id));

        $this->assertContains('b10', $numbers);
    }

    public function test_person_with_no_children_yields_only_the_progenitor(): void
    {
        $person = Person::factory()->create();

        $data = $this->generateFor($person->id)->get('reportData');

        $this->assertCount(1, $data);
        $this->assertSame('a1', $data[0]['number']);
        $this->assertSame(0, $data[0]['depth']);
    }
}
