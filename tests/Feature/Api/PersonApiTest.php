<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PersonApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
        // Set current_team_id so BelongsToTenant scope works in API context
        $team = $this->user->ownedTeams()->first();
        if ($team) {
            $this->user->forceFill(['current_team_id' => $team->id])->save();
            $this->user->setRelation('currentTeam', $team);
        }
        Sanctum::actingAs($this->user);
    }

    public function test_index_returns_paginated_people(): void
    {
        Person::factory()->count(3)->create();

        $response = $this->getJson('/api/people');

        $response->assertOk()
            ->assertJsonStructure(['data', 'meta', 'links']);
    }

    public function test_index_search_parameter_is_accepted(): void
    {
        $response = $this->getJson('/api/people?search=Alice');

        $response->assertOk();
        $this->assertArrayHasKey('data', $response->json());
    }

    public function test_store_creates_person(): void
    {
        $payload = [
            'givn' => 'Jane',
            'surn' => 'Doe',
            'sex' => 'F',
            'name' => 'Jane Doe',
        ];

        $response = $this->postJson('/api/people', $payload);

        $response->assertCreated()
            ->assertJsonFragment(['givn' => 'Jane']);

        $this->assertDatabaseHas('people', ['givn' => 'Jane', 'surn' => 'Doe']);
    }

    public function test_store_validates_required_sex(): void
    {
        $response = $this->postJson('/api/people', ['givn' => 'Jane']);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['sex']);
    }

    public function test_show_returns_person(): void
    {
        $person = Person::factory()->create();

        $response = $this->getJson("/api/people/{$person->id}");

        $response->assertOk()
            ->assertJsonFragment(['id' => $person->id]);
    }

    public function test_show_returns_404_for_unknown_person(): void
    {
        $this->getJson('/api/people/99999')->assertNotFound();
    }

    public function test_update_modifies_person(): void
    {
        $person = Person::factory()->create(['givn' => 'Old']);

        $response = $this->putJson("/api/people/{$person->id}", [
            'sex' => $person->sex,
            'givn' => 'New',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['givn' => 'New']);

        $this->assertDatabaseHas('people', ['id' => $person->id, 'givn' => 'New']);
    }

    public function test_destroy_deletes_person(): void
    {
        $person = Person::factory()->create();

        $this->deleteJson("/api/people/{$person->id}")->assertNoContent();

        $this->assertSoftDeleted('people', ['id' => $person->id]);
    }

    public function test_unauthenticated_request_returns_401(): void
    {
        $this->app['auth']->forgetGuards();

        $response = $this->getJson('/api/people');

        $response->assertUnauthorized();
    }

    public function test_health_endpoint_is_public(): void
    {
        $this->app['auth']->forgetGuards();

        $this->getJson('/api/health')->assertOk()->assertJsonFragment(['status' => 'ok']);
    }
}
