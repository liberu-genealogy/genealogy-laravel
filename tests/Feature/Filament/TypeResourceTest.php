<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\TypeResource;
use App\Filament\Resources\TypeResource\Pages\CreateType;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TypeResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_configuration()
    {
        $this->assertEquals(Type::class, TypeResource::getModel());
        $formFields = TypeResource::form(null)->getSchema();
        $this->assertCount(3, $formFields);
        $tableColumns = TypeResource::table(null)->getColumns();
        $this->assertCount(5, $tableColumns);
    }

    public function test_user_can_create_type()
    {
        $this->actingAsUser();
        $response = $this->post(route('filament.resources.types.create'), [
            'name' => 'Test Type',
            'description' => 'This is a test type description.',
            'is_active' => 1,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('types', [
            'name' => 'Test Type',
            'description' => 'This is a test type description.',
            'is_active' => 1,
        ]);
    }

    public function test_type_creation_requires_valid_data()
    {
        $this->actingAsUser();
        $response = $this->post(route('filament.resources.types.create'), [
            'name' => '',
            'description' => '',
            'is_active' => 'invalid',
        ]);
        $response->assertSessionHasErrors(['name', 'description', 'is_active']);
    }

    protected function actingAsUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    }
}
