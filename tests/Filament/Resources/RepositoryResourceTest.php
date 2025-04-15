<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\RepositoryResource;
use App\Models\Repository;
use Tests\TestCase;

class RepositoryResourceTest extends TestCase
{
    public function test_form_schema_is_correct(): void
    {
        $form = RepositoryResource::form(app(\Filament\Forms\Form::class));
        $schema = collect($form->getSchema());

        $expectedFields = [
            'group', 'gid', 'name', 'description', 'date', 'is_active', 'type_id', 'repo', 'addr_id', 'rin', 'phon', 'email', 'fax', 'www',
        ];

        foreach ($expectedFields as $field) {
            $this->assertTrue($schema->contains(fn ($component): bool => $component->getName() === $field), "{$field} is missing in the form schema.");
        }
    }

    public function test_table_columns_are_correct(): void
    {
        $table = RepositoryResource::table(app(\Filament\Tables\Table::class));
        $columns = collect($table->getColumns());

        $expectedColumns = [
            'group', 'gid', 'name', 'date', 'is_active', 'type_id', 'repo', 'addr_id', 'rin', 'phon', 'email', 'fax', 'www', 'created_at', 'updated_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue($columns->contains(fn ($component): bool => $component->getName() === $column), "{$column} is missing in the table columns.");
        }
    }

    public function test_navigation_icon_is_correct(): void
    {
        $this->assertEquals('heroicon-o-rectangle-stack', RepositoryResource::$navigationIcon);
    }

    public function test_model_binding_is_correct(): void
    {
        $this->assertEquals(Repository::class, RepositoryResource::$model);
    }

    public function test_page_routes_are_correct(): void
    {
        $pages = RepositoryResource::getPages();

        $this->assertEquals('/', $pages['index']);
        $this->assertEquals('/create', $pages['create']);
        $this->assertEquals('/{record}/edit', $pages['edit']);
    }
}
