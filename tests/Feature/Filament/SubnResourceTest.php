<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\SubnResource;
use App\Models\Subn;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubnResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_configuration(): void
    {
        $this->assertEquals(Subn::class, SubnResource::getModel());
        $this->assertCount(7, SubnResource::form(null)->getSchema());
        $this->assertCount(9, SubnResource::table(null)->getColumns());
    }

    public function test_user_can_create_subn(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->post(route('filament.resources.subns.create'), [
            'subm' => 'Test Subm',
            'famf' => 'Test Famf',
            'temp' => 'Test Temp',
            'ance' => 'Test Ance',
            'desc' => 'Test Desc',
            'ordi' => 'Test Ordi',
            'rin'  => 'Test Rin',
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('subns', [
            'subm' => 'Test Subm',
            'famf' => 'Test Famf',
        ]);
    }

    public function test_user_can_edit_subn(): void
    {
        $user = User::factory()->create();
        $subn = Subn::factory()->create();
        $this->actingAs($user);
        $response = $this->put(route('filament.resources.subns.edit', $subn), [
            'subm' => 'Updated Subm',
            'famf' => 'Updated Famf',
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('subns', [
            'id'   => $subn->id,
            'subm' => 'Updated Subm',
            'famf' => 'Updated Famf',
        ]);
    }

    public function test_subns_are_listed_correctly(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        Subn::factory()->count(3)->create();
        $response = $this->get(route('filament.resources.subns.index'));
        $response->assertStatus(200);
        $subns = Subn::all();
        foreach ($subns as $subn) {
            $response->assertSee($subn->subm, false);
        }
    }
}
