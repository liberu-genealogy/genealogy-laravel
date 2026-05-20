<?php

namespace Tests\Feature\Filament;

use App\Filament\App\Resources\TypeResource;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TypeResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_has_correct_model(): void
    {
        $this->assertEquals(Type::class, TypeResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(TypeResource::getNavigationLabel());
    }

    public function test_type_can_be_created_in_database(): void
    {
        $type = Type::create([
            'name'        => 'Test Type',
            'description' => 'This is a test type description.',
            'is_active'   => 1,
        ]);

        $this->assertDatabaseHas('types', [
            'name'        => 'Test Type',
            'description' => 'This is a test type description.',
        ]);
        $this->assertTrue($type->is_active);
    }
}
