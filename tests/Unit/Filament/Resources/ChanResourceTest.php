<?php

namespace Tests\Unit\Filament\Resources;

use App\Filament\Resources\ChanResource;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use PHPUnit\Framework\TestCase;

class ChanResourceTest extends TestCase
{
    public function test_form_schema_contains_expected_fields(): void
    {
        $form = ChanResource::form(null);
        $schema = $form->getSchema();

        $expectedFields = [
            'group' => TextInput::class,
            'gid'   => TextInput::class,
            'date'  => TextInput::class,
            'time'  => TextInput::class,
        ];

        foreach ($expectedFields as $fieldName => $fieldClass) {
            $field = collect($schema)->firstWhere('name', $fieldName);
            $this->assertNotNull($field, "Field {$fieldName} does not exist.");
            $this->assertInstanceOf($fieldClass, $field, "Field {$fieldName} is not of type {$fieldClass}.");
            if ($fieldName !== 'gid') {
                $this->assertEquals(255, $field->getMaxLength(), "Field {$fieldName} does not have the correct maxLength.");
            }
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
        $this->assertNotNull($editAction, 'Edit action does not exist.');
        $this->assertInstanceOf(EditAction::class, $editAction, 'Edit action is not of type EditAction.');

        $deleteBulkAction = collect($bulkActions)->firstWhere('name', 'delete');
        $this->assertNotNull($deleteBulkAction, 'Delete bulk action does not exist.');
        $this->assertInstanceOf(DeleteBulkAction::class, $deleteBulkAction, 'Delete bulk action is not of type DeleteBulkAction.');
    }
}
