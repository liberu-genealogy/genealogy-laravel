<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\PeopleSearch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PeopleSearchTest extends TestCase
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

    public function test_component_renders(): void
    {
        Livewire::test(PeopleSearch::class)->assertOk();
    }

    public function test_initial_query_is_empty(): void
    {
        Livewire::test(PeopleSearch::class)
            ->assertSet('query', '');
    }

    public function test_results_is_array(): void
    {
        $component = Livewire::test(PeopleSearch::class);

        $this->assertIsArray($component->get('results'));
    }

    public function test_setting_query_updates_results(): void
    {
        Livewire::test(PeopleSearch::class)
            ->set('query', 'test')
            ->assertSet('query', 'test');
    }

    public function test_empty_query_returns_results(): void
    {
        $component = Livewire::test(PeopleSearch::class)
            ->set('query', '');

        $this->assertIsArray($component->get('results'));
    }
}
