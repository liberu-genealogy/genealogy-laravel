<?php

namespace Tests\Feature\Filament\Resources;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Filament\Resources\PersonAliaResource;
use App\Models\PersonAlia;

class PersonAliaResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_fields_are_correctly_defined()
    {
        $form = PersonAliaResource::form(Livewire::mock());
        $schema = collect($form->getSchema());

        $this->assertNotNull($schema->firstWhere('name', 'group')->maxLength(255));
        $this->assertNotNull($schema->firstWhere('name', 'gid')->numeric());
        $this->assertNotNull($schema->firstWhere('name', 'alia')->maxLength(255));
        $this->assertTrue($schema->firstWhere('name', 'import_confirm')->isRequired());
        $this->assertEquals(0, $schema->firstWhere('name', 'import_confirm')->getDefault());
    }

    public function test_table_configuration_is_correct()
    {
        $table = PersonAliaResource::table(Livewire::mock());
        $columns = $table->getColumns();

        $this->assertTrue($columns->firstWhere('name', 'group')->isSearchable());
        $this->assertTrue($columns->firstWhere('name', 'gid')->isNumeric()->isSortable());
        $this->assertTrue($columns->firstWhere('name', 'alia')->isSearchable());
        $this->assertTrue($columns->firstWhere('name', 'import_confirm')->isNumeric()->isSortable());
        $this->assertTrue($columns->firstWhere('name', 'created_at')->isDateTime()->isSortable());
        $this->assertTrue($columns->firstWhere('name', 'updated_at')->isDateTime()->isSortable());

        $actions = $table->getActions();
        $this->assertNotEmpty($actions->firstWhere('name', 'edit'));

        $bulkActions = $table->getBulkActions();
        $this->assertNotEmpty($bulkActions->firstWhere('name', 'delete'));
    }
}
