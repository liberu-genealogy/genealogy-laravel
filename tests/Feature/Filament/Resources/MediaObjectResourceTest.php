<?php

namespace Tests\Feature\Filament\Resources;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\MediaObject;
use Livewire\Livewire;
use App\Filament\Resources\MediaObjectResource;
use Illuminate\Foundation\Testing\WithFaker;

class MediaObjectResourceTest extends TestCase
/**
 * Tests the functionality of the MediaObjectResource, focusing on form schema correctness and table configuration.
 */
{
    use RefreshDatabase, WithFaker;

    public function test_form_schema_contains_correct_fields()
    {
        $form = MediaObjectResource::form(Livewire::mock());
        $schema = collect($form->getSchema());

        $this->assertNotNull($schema->firstWhere('name', 'gid')->numeric());
        $this->assertEquals(255, $schema->firstWhere('name', 'group')->getMaxLength());
        $this->assertEquals(255, $schema->firstWhere('name', 'titl')->getMaxLength());
        $this->assertEquals(255, $schema->firstWhere('name', 'obje_id')->getMaxLength());
        $this->assertEquals(255, $schema->firstWhere('name', 'rin')->getMaxLength());
    }

    public function test_table_configuration()
    /**
     * Tests that the form schema for MediaObjectResource contains the correct fields with appropriate configurations.
     */
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
    /**
     * Tests the table configuration of MediaObjectResource, ensuring columns and actions are correctly defined.
     */
