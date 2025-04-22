<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\Resources\MediaObjectResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class MediaObjectResourceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_form_schema_contains_correct_fields(): void
    {
        $form = MediaObjectResource::form(Livewire::mock());
        $schema = collect($form->getSchema());

        $this->assertNotNull($schema->firstWhere('name', 'gid')->numeric());
        $this->assertEquals(255, $schema->firstWhere('name', 'group')->getMaxLength());
        $this->assertEquals(255, $schema->firstWhere('name', 'titl')->getMaxLength());
        $this->assertEquals(255, $schema->firstWhere('name', 'obje_id')->getMaxLength());
        $this->assertEquals(255, $schema->firstWhere('name', 'rin')->getMaxLength());
    }

    public function test_table_configuration(): void
    {
        $table = MediaObjectResource::table(Livewire::mock());
        $columns = $table->getColumns();

        $this->assertNotEmpty($columns->firstWhere('name', 'gid')->numeric());
        $this->assertTrue($columns->firstWhere('name', 'group')->isSearchable());
        $this->assertTrue($columns->firstWhere('name', 'titl')->isSearchable());
        $this->assertTrue($columns->firstWhere('name', 'obje_id')->isSearchable());
        $this->assertTrue($columns->firstWhere('name', 'created_at')->isSortable());
        $this->assertTrue($columns->firstWhere('name', 'updated_at')->isSortable());
        $this->assertTrue($columns->firstWhere('name', 'rin')->isSearchable());

        $actions = $table->getActions();
        $this->assertNotEmpty($actions->firstWhere('name', 'edit'));

        $bulkActions = $table->getBulkActions();
        $this->assertNotEmpty($bulkActions->firstWhere('name', 'delete'));
    }
}
