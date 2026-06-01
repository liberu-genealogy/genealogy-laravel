<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\SourceRefEvenResource;
use App\Models\SourceRefEven;
use Tests\TestCase;

class SourceRefEvenResourceTest extends TestCase
{
    public function test_form_schema_is_correct(): void
    {
        $form = SourceRefEvenResource::form(app(\Filament\Forms\Form::class));
        $schema = collect($form->getSchema());

        $expectedFields = [
            'group' => ['maxLength' => 255],
            'gid'   => ['numeric' => true],
            'even'  => ['maxLength' => 255],
            'role'  => ['maxLength' => 255],
        ];

        foreach ($expectedFields as $fieldName => $attributes) {
            $field = $schema->firstWhere('name', $fieldName);
            $this->assertNotNull($field, "{$fieldName} field is missing.");
            foreach ($attributes as $attribute => $value) {
                $this->assertEquals($value, $field->$attribute, "{$fieldName} field does not have the correct {$attribute}.");
            }
        }
    }

    public function test_table_columns_are_correct(): void
    {
        $table = SourceRefEvenResource::table(app(\Filament\Tables\Table::class));
        $columns = collect($table->getColumns());

        $expectedColumns = ['group', 'gid', 'even', 'role', 'created_at', 'updated_at'];

        foreach ($expectedColumns as $columnName) {
            $this->assertTrue($columns->contains(fn ($column): bool => $column->getName() === $columnName), "{$columnName} column is missing.");
        }
    }

    public function test_navigation_icon_is_correct(): void
    {
        $this->assertEquals('heroicon-o-rectangle-stack', SourceRefEvenResource::$navigationIcon);
    }

    public function test_model_binding_is_correct(): void
    {
        $this->assertEquals(SourceRefEven::class, SourceRefEvenResource::$model);
    }

    public function test_page_routes_are_correct(): void
    {
        $pages = SourceRefEvenResource::getPages();

        $this->assertEquals('/', $pages['index']);
        $this->assertEquals('/create', $pages['create']);
        $this->assertEquals('/{record}/edit', $pages['edit']);
    }
}
