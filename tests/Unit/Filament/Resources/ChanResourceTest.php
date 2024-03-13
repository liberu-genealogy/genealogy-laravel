<?php

namespace Tests\Unit\Filament\Resources;

use PHPUnit\Framework\TestCase;
use App\Filament\Resources\ChanResource;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;

class ChanResourceTest extends TestCase
{
    public function test_form_schema_contains_expected_fields(): void
    {
        $form = ChanResource::form(null);
        $schema = $form->getSchema();

        $expectedFields = [
            'group' => TextInput::class,
            'gid' => TextInput::class,
            'date' => TextInput::class,
            'time' => TextInput::class,
        ];

        foreach ($expectedFields as $fieldName => $fieldClass) {
            $field = $this->assertFieldExists($schema, $fieldName);
            $this->assertFieldType($field, $fieldClass, $fieldName);
            $this->assertFieldAttributes($field, $fieldName, $fieldName === 'gid' ? null : 255);
        }
    }

    public function test_table_configuration_includes_correct_columns_and_actions(): void
    {
        $table = ChanResource::table(null);
        $columns = $table->getColumns();
        $actions = $table->getActions();
        $bulkActions = $table->getBulkActions();

        $expectedColumns = ['group', 'gid', 'date', 'time', 'created_at', 'updated_at'];
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
    private function assertFieldExists($schema, $fieldName)
    {
        $field = collect($schema)->firstWhere('name', $fieldName);
        $this->assertNotNull($field, "Field {$fieldName} does not exist.");
        return $field;
    }

    private function assertFieldType($field, $fieldClass, $fieldName)
    {
        $this->assertInstanceOf($fieldClass, $field, "Field {$fieldName} is not of type {$fieldClass}.");
    }

    private function assertFieldAttributes($field, $fieldName, $expectedLength = null)
    {
        if ($expectedLength !== null) {
            $this->assertEquals($expectedLength, $field->getMaxLength(), "Field {$fieldName} does not have the correct maxLength.");
        }
    }

    private function assertColumnExists($columns, $columnName)
    {
        $column = collect($columns)->firstWhere('name', $columnName);
        $this->assertNotNull($column, "Column {$columnName} does not exist.");
        return $column;
    }

    private function assertColumnType($column, $columnName)
    {
        $this->assertInstanceOf(TextColumn::class, $column, "Column {$columnName} is not of type TextColumn.");
    }

    private function assertActionExists($actions, $actionName, $actionType)
    {
        $action = collect($actions)->firstWhere('name', $actionName);
        $this->assertNotNull($action, "{$actionName} action does not exist.");
        $this->assertInstanceOf($actionType, $action, "{$actionName} action is not of type {$actionType}.");
    }
