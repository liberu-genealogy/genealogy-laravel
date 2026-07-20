<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\FanChartComponent;
use App\Models\Family;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FanChartComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_can_be_rendered_without_a_person(): void
    {
        Livewire::test(FanChartComponent::class)
            ->assertStatus(200)
            ->assertSet('tree', []);
    }

    /**
     * The page passes the selected person (?person_id) into
     * <livewire:fan-chart-component :person="$person" />, but the component had
     * no person property and rendered Person::all() — so it ignored the
     * selection and always drew the same thing. It now roots an ancestor tree at
     * the selected person.
     */
    public function test_it_roots_the_chart_at_the_selected_person_with_ancestors(): void
    {
        $father = Person::factory()->create(['givn' => 'Grandpa', 'surn' => 'Root']);
        $mother = Person::factory()->create(['givn' => 'Grandma', 'surn' => 'Root']);
        $family = Family::factory()->create(['husband_id' => $father->id, 'wife_id' => $mother->id]);
        $root = Person::factory()->create(['givn' => 'Child', 'surn' => 'Root', 'child_in_family_id' => $family->id]);

        // A second, unrelated person that must NOT become the root.
        Person::factory()->create(['givn' => 'Stranger', 'surn' => 'Other']);

        $tree = Livewire::test(FanChartComponent::class, ['person' => $root])->get('tree');

        $this->assertSame($root->id, $tree['id']);
        $this->assertSame('Child Root', $tree['name']);

        $ancestorIds = array_column($tree['children'], 'id');
        $this->assertContains($father->id, $ancestorIds);
        $this->assertContains($mother->id, $ancestorIds);
    }
}
