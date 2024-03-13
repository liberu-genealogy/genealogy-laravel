<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\SourceDataResource;
use App\Models\SourceData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceDataResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_schema_is_correct()
    {
        $form = SourceDataResource::form(app(\Filament\Forms\Form::class));
        $schema = collect($form->getSchema());

        $expectedFields = [
            ['name' => 'group', 'type' => 'TextInput', 'attributes' => ['maxLength' => 255]],
            ['name' => 'gid', 'type' => 'TextInput', 'attributes' => ['numeric' => true]],
            ['name' => 'date', 'type' => 'TextInput', 'attributes' => ['maxLength' => 255]],
            ['name' => 'text', 'type' => 'TextInput', 'attributes' => ['maxLength' => 255]],
            ['name' => 'agnc', 'type' => 'TextInput', 'attributes' => ['maxLength' => 255]],
        ];

        foreach ($expectedFields as $field) {
            $component = $schema->firstWhere('name', $field['name']);
            $this->assertNotNull($component, "{$field['name']} is missing in the form schema.");
            $this->assertEquals($field['type'], class_basename($component), "{$field['name']} is not of type {$field['type']}.");
            foreach ($field['attributes'] as $attribute => $value) {
                $this->assertEquals($value, $component->$attribute, "{$field['name']} does not have the correct {$attribute}.");
            }
        }
    }

    public function test_table_columns_are_correct()
    {
        $table = SourceDataResource::table(app(\Filament\Tables\Table::class));
        $columns = collect($table->getColumns());

        $expectedColumns = [
            'group', 'gid', 'date', 'text', 'agnc', 'created_at', 'updated_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue($columns->contains(fn ($component) => $component->getName() === $column), "{$column} is missing in the table columns.");
        }
    }

    public function test_navigation_icon_is_correct()
    {
        $this->assertEquals('heroicon-o-rectangle-stack', SourceDataResource::$navigationIcon);
    }

    public function test_model_binding_is_correct()
    {
        $this->assertEquals(SourceData::class, SourceDataResource::$model);
    }

    public function test_page_routes_are_correct()
    {
        $pages = SourceDataResource::getPages();

        $this->assertEquals('/', $pages['index']);
        $this->assertEquals('/create', $pages['create']);
        $this->assertEquals('/{record}/edit', $pages['edit']);
    }
}
