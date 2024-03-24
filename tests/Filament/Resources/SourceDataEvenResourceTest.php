<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\SourceDataEvenResource;
use App\Models\SourceDataEven;
use Filament\Forms\Components\TextInput;
use Tests\TestCase;

/**
 * Tests the functionality and form schema of the SourceDataEvenResource class.
 */
class SourceDataEvenResourceTest extends TestCase
{
 * Tests if the form schema contains all expected fields.
 */
{
    public function test_form_schema_is_correct()
    {
        $form = SourceDataEvenResource::form(app(\Filament\Forms\Form::class));
        $schema = collect($form->getSchema());

        $expectedFields = [
            ['name' => 'group', 'type' => TextInput::class, 'attributes' => ['maxLength' => 255]],
            ['name' => 'gid', 'type' => TextInput::class, 'attributes' => ['maxLength' => 255]],
            ['name' => 'date', 'type' => TextInput::class, 'attributes' => ['maxLength' => 255]],
            ['name' => 'plac', 'type' => TextInput::class, 'attributes' => ['maxLength' => 255]],
        ];

        foreach ($expectedFields as $field) {
            $component = $schema->firstWhere('name', $field['name']);
            $this->assertNotNull($component, "{$field['name']} is missing in the form schema.");
            $this->assertInstanceOf($field['type'], $component, "{$field['name']} is not of type {$field['type']}.");
            foreach ($field['attributes'] as $attribute => $value) {
                $this->assertEquals($value, $component->$attribute, "{$field['name']} does not have the correct {$attribute}.");
            }
        }
    }

    public function test_table_columns_are_correct()
    {
        $table = SourceDataEvenResource::table(app(\Filament\Tables\Table::class));
        $columns = collect($table->getColumns());

        $expectedColumns = [
            'group', 'gid', 'date', 'plac', 'created_at', 'updated_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue($columns->contains(fn ($component) => $component->getName() === $column), "{$column} is missing in the table columns.");

        foreach ($expectedColumns as $column) {
            $this->assertTrue($columns->contains(fn ($component) => $component->getName() === $column), "{$column} is missing in the table columns.");
        }
    }

    public function test_navigation_icon_is_correct()
    {
        $this->assertEquals('heroicon-o-rectangle-stack', SourceDataEvenResource::$navigationIcon);
    }

    public function test_model_binding_is_correct()
/**
 * Tests if the navigation icon is set correctly.
 */
    {
        $this->assertEquals(SourceDataEven::class, SourceDataEvenResource::$model);
    }

    public function test_page_routes_are_correct()
    {
        $pages = SourceDataEvenResource::getPages();

        $this->assertEquals('/', $pages['index']);
        $this->assertEquals('/create', $pages['create']);
        $this->assertEquals('/{record}/edit', $pages['edit']);
    }
}
/**
 * Tests if the model binding is correctly set to the SourceDataEven model.
 */
/**
 * Tests if the page routes are correctly configured.
 */
