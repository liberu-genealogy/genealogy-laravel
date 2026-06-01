<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\FamilyTreeBuilder;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FamilyTreeBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_renders(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Livewire::test(FamilyTreeBuilder::class)
                ->assertOk();
    }

    public function test_tree_data_is_loaded_on_mount(): void
    {
        $user   = User::factory()->withPersonalTeam()->create();
        $person = Person::factory()->create();
        $this->actingAs($user);

        $component = Livewire::test(FamilyTreeBuilder::class);

        $treeData = $component->get('treeData');
        $this->assertIsArray($treeData);
    }

    public function test_can_select_a_person(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $team = $user->ownedTeams()->first();
        if ($team) {
            $user->forceFill(['current_team_id' => $team->id])->save();
        }
        $this->actingAs($user);

        $person = Person::factory()->create();

        $component = Livewire::test(FamilyTreeBuilder::class)
                             ->call('selectPerson', $person->id);

        $selected = $component->get('selectedPerson');
        $this->assertNotNull($selected);
    }
}
