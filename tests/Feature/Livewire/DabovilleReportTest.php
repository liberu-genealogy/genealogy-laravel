<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\DabovilleReport;
use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class DabovilleReportTest extends TestCase
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
        return Livewire::test(DabovilleReport::class)
            ->set('data.person', $personId)
            ->call('generateReport');
    }

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
        Livewire::test(DabovilleReport::class)->assertOk();
    }

    public function test_progenitor_is_1_and_children_are_dot_separated(): void
    {
        $progenitor = $this->progenitorWithChildren(2);

        $this->assertSame(['1', '1.1', '1.2'], $this->numbers($this->generateFor($progenitor->id)));
    }

    public function test_numbering_descends_multiple_generations(): void
    {
        $progenitor = Person::factory()->create();
        $f1 = Family::factory()->create(['husband_id' => $progenitor->id]);
        $child = Person::factory()->create(['child_in_family_id' => $f1->id, 'birthday' => '1900-01-01']);
        $f2 = Family::factory()->create(['husband_id' => $child->id]);
        Person::factory()->create(['child_in_family_id' => $f2->id, 'birthday' => '1930-01-01']);

        $this->assertSame(['1', '1.1', '1.1.1'], $this->numbers($this->generateFor($progenitor->id)));
    }

    public function test_tenth_child_is_plain_decimal_no_letters(): void
    {
        // d'Aboville's whole advantage over Henry: 10th child is just ".10".
        $progenitor = $this->progenitorWithChildren(10);

        $numbers = $this->numbers($this->generateFor($progenitor->id));

        $this->assertContains('1.10', $numbers);
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
}
