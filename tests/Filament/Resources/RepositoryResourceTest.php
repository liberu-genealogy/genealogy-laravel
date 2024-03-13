<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\RepositoryResource;
use App\Models\Repository;
use Tests\TestCase;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;

class RepositoryResourceTest extends TestCase
/**
 * Tests the functionality and form schema of the RepositoryResource class.
 */
{
    public function test_form_schema_is_correct()
    {
        $form = RepositoryResource::form(app(\Filament\Forms\Form::class));
        $schema = collect($form->getSchema());

        $expectedFields = [
            'group', 'gid', 'name', 'description', 'date', 'is_active', 'type_id', 'repo', 'addr_id', 'rin', 'phon', 'email', 'fax', 'www'
        ];

        foreach ($expectedFields as $field) {
            $this->assertTrue($schema->contains(fn ($component) => $component->getName() === $field), "{$field} is missing in the form schema.");
        }
    }

    public function test_table_columns_are_correct()
/**
 * Tests if the form schema contains all expected fields.
 */
    {
        $table = RepositoryResource::table(app(\Filament\Tables\Table::class));
        $columns = collect($table->getColumns());

        $expectedColumns = [
            'group', 'gid', 'name', 'date', 'is_active', 'type_id', 'repo', 'addr_id', 'rin', 'phon', 'email', 'fax', 'www', 'created_at', 'updated_at'
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue($columns->contains(fn ($component) => $component->getName() === $column), "{$column} is missing in the table columns.");
        }
    }

    public function test_navigation_icon_is_correct()
/**
 * Tests if the table columns contain all expected fields.
 */
    {
        $this->assertEquals('heroicon-o-rectangle-stack', RepositoryResource::$navigationIcon);
    }

    public function test_model_binding_is_correct()
    {
        $this->assertEquals(Repository::class, RepositoryResource::$model);
    }

    public function test_page_routes_are_correct()
    {
        $pages = RepositoryResource::getPages();

        $this->assertEquals('/', $pages['index']);
        $this->assertEquals('/create', $pages['create']);
        $this->assertEquals('/{record}/edit', $pages['edit']);
    }
}
/**
 * Tests if the model binding is correctly set to the Repository model.
 */
/**
 * Tests if the page routes are correctly configured.
 */
