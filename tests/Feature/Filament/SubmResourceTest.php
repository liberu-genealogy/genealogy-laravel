<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\SubmResource;
use App\Filament\Resources\SubmResource\Pages\CreateSubm;
use App\Filament\Resources\SubmResource\Pages\EditSubm;
use App\Filament\Resources\SubmResource\Pages\ListSubms;
use App\Models\Subm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubmResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function resource_is_correctly_configured()
    {
        $this->assertEquals(Subm::class, SubmResource::getModel());
        $formFields = SubmResource::form(null)->getSchema();
        $this->assertCount(11, $formFields);
        $tableColumns = SubmResource::table(null)->getColumns();
        $this->assertCount(13, $tableColumns);
    }

    /** @test */
    public function user_can_create_subm()
    {
        $this->actingAsUser();
        $response = $this->post(route('filament.resources.subms.create'), [
            'group' => 'Test Group',
            'gid' => '123',
            'name' => 'Test Name',
            // Add other fields accordingly
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('subms', [
            'group' => 'Test Group',
            // Add other fields accordingly
        ]);
    }

    /** @test */
    public function user_can_edit_subm()
    {
        $this->actingAsUser();
        $subm = Subm::factory()->create();
        $response = $this->put(route('filament.resources.subms.edit', $subm), [
            'name' => 'Updated Name',
            // Add other fields accordingly
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('subms', [
            'id' => $subm->id,
            'name' => 'Updated Name',
            // Add other fields accordingly
        ]);
    }

    /** @test */
    public function subms_are_listed_correctly()
    {
        $this->actingAsUser();
        Subm::factory()->count(5)->create();
        $response = $this->get(route('filament.resources.subms.index'));
        $response->assertStatus(200);
        $response->assertSee(Subm::first()->name);
        // Add more assertions as necessary
    }

    protected function actingAsUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    }
}
