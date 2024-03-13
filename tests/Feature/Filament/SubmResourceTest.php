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
/**
 * This file contains tests for the SubmResource class.
 *
 * It includes tests for the creation, editing, and listing of subms within the genealogy application.
 */
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
    /**
     * Tests the configuration of the SubmResource.
     *
     * This function checks if the SubmResource is correctly configured by comparing the model class and counting form fields and table columns.
     */
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
    /**
     * Tests whether a user can create a subm.
     *
     * This function simulates a user attempting to create a subm and checks whether the operation is successful.
     */
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
    /**
     * Tests whether a user can edit a subm.
     *
     * This function simulates a user attempting to edit a subm and checks whether the operation is successful.
     */
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
    /**
     * Tests the listing of subms.
     *
     * This function simulates a user viewing the list of subms and checks whether the subms are listed correctly.
     */
