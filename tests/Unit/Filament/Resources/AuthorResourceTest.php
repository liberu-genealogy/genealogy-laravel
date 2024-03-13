<?php

namespace Tests\Unit\Filament\Resources;

use PHPUnit\Framework\TestCase;
use App\Filament\Resources\AuthorResource;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;

class AuthorResourceTest extends TestCase
{
    public function test_form_schema_contains_expected_fields(): void
    {
        $form = AuthorResource::form(null);
        $schema = $form->getSchema();

        $expectedFields = [
            'name' => ['type' => TextInput::class, 'required' => true, 'maxLength' => 255],
            'description' => ['type' => TextInput::class, 'required' => true, 'maxLength' => 255],
            'is_active' => ['type' => TextInput::class, 'required' => true, 'numeric' => true],
        ];

        foreach ($expectedFields as $fieldName => $fieldAttributes) {
            $field = collect($schema)->firstWhere('name', $fieldName);
            $this->assertNotNull($field, "Field {$fieldName} does not exist.");
            $this->assertInstanceOf($fieldAttributes['type'], $field, "Field {$fieldName} is not of type {$fieldAttributes['type']}.");
            $this->assertEquals($fieldAttributes['maxLength'] ?? null, $field->getMaxLength(), "Field {$fieldName} does not have the correct maxLength.");
            $this->assertEquals($fieldAttributes['required'] ?? false, $field->isRequired(), "Field {$fieldName} does not have the correct required status.");
            $this->assertEquals($fieldAttributes['numeric'] ?? false, method_exists($field, 'isNumeric') && $field->isNumeric(), "Field {$fieldName} does not have the correct numeric status.");
        }
    }

    public function test_table_configuration_includes_correct_columns_and_actions(): void
    {
        $table = AuthorResource::table(null);
        $columns = $table->getColumns();
        $actions = $table->getActions();
        $bulkActions = $table->getBulkActions();

        $expectedColumns = ['name', 'description', 'is_active', 'created_at', 'updated_at'];
        foreach ($expectedColumns as $columnName) {
            $column = collect($columns)->firstWhere('name', $columnName);
            $this->assertNotNull($column, "Column {$columnName} does not exist.");
            $this->assertInstanceOf(TextColumn::class, $column, "Column {$columnName} is not of type TextColumn.");
        }

        $editAction = collect($actions)->firstWhere('name', 'edit');
        $this->assertNotNull($editAction, "Edit action does not exist.");
        $this->assertInstanceOf(EditAction::class, $editAction, "Edit action is not of type EditAction.");

        $deleteBulkAction = collect($bulkActions)->firstWhere('name', 'delete');
        $this->assertNotNull($deleteBulkAction, "Delete bulk action does not exist.");
        $this->assertInstanceOf(DeleteBulkAction::class, $deleteBulkAction, "Delete bulk action is not of type DeleteBulkAction.");
    }
}
