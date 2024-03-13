<?php

namespace Tests\Feature\Filament\Resources;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Filament\Resources\NoteResource;

class NoteResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_fields_are_correctly_defined()
    {
        $form = NoteResource::form(Livewire::mock());
        $schema = collect($form->getSchema());

        $this->assertNotNull($schema->firstWhere('name', 'name'));
        $this->assertNotNull($schema->firstWhere('name', 'description'));
        $this->assertNotNull($schema->firstWhere('name', 'date'));
        $this->assertNotNull($schema->firstWhere('name', 'type_id')->numeric());
        $this->assertNotNull($schema->firstWhere('name', 'is_active')->numeric());
        $this->assertNotNull($schema->firstWhere('name', 'group'));
        $this->assertNotNull($schema->firstWhere('name', 'gid'));
        $this->assertNotNull($schema->firstWhere('name', 'note'));
        $this->assertNotNull($schema->firstWhere('name', 'rin'));
    }

    public function test_table_configuration_is_correct()
    {
        $table = NoteResource::table(Livewire::mock());
        $columns = $table->getColumns();

        $this->assertNotEmpty($columns->firstWhere('name', 'name')->isSearchable());
        $this->assertNotEmpty($columns->firstWhere('name', 'date')->isSortable());
        $this->assertNotEmpty($columns->firstWhere('name', 'type_id')->isSortable());
        $this->assertNotEmpty($columns->firstWhere('name', 'is_active')->isSortable());
        $this->assertNotEmpty($columns->firstWhere('name', 'group')->isSearchable());
        $this->assertNotEmpty($columns->firstWhere('name', 'gid')->isSearchable());
        $this->assertNotEmpty($columns->firstWhere('name', 'rin')->isSearchable());
        $this->assertNotEmpty($columns->firstWhere('name', 'created_at')->isSortable());
        $this->assertNotEmpty($columns->firstWhere('name', 'updated_at')->isSortable());

        $actions = $table->getActions();
        $this->assertNotEmpty($actions->firstWhere('name', 'edit'));

        $bulkActions = $table->getBulkActions();
        $this->assertNotEmpty($bulkActions->firstWhere('name', 'delete'));
    }
}
