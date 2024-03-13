<?php

namespace Tests\Unit\Filament\Resources;

use PHPUnit\Framework\TestCase;
use App\Filament\Resources\AddrResource;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;

class AddrResourceTest extends TestCase
/**
 * This file contains tests for the AddrResource class, ensuring that the form schema and table configuration are correctly defined.
 */
 * Tests whether the form schema contains the expected fields with correct configurations for the AddrResource.
 */
class AddrResourceTest extends TestCase
{
    public function test_form_schema_contains_expected_fields(): void
    {
        $form = AddrResource::form(null);
        $schema = $form->getSchema();

        $expectedFields = [
            'adr1' => TextInput::class,
            'adr2' => TextInput::class,
            'city' => TextInput::class,
            'stae' => TextInput::class,
            'post' => TextInput::class,
            'ctry' => TextInput::class,
        ];

        foreach ($expectedFields as $fieldName => $fieldClass) {
            $field = collect($schema)->firstWhere('name', $fieldName);
            $this->assertNotNull($field, "Field {$fieldName} does not exist.");
            $this->assertInstanceOf($fieldClass, $field, "Field {$fieldName} is not of type {$fieldClass}.");
            $this->assertEquals(255, $field->getMaxLength(), "Field {$fieldName} does not have the correct maxLength.");
        }
    }

    public function test_table_configuration_includes_correct_columns_and_actions(): void
    {
        $table = AddrResource::table(null);
        $columns = $table->getColumns();
        $actions = $table->getActions();
        $bulkActions = $table->getBulkActions();

        $expectedColumns = ['adr1', 'adr2', 'city', 'stae', 'post', 'ctry', 'created_at', 'updated_at'];
        foreach ($expectedColumns as $columnName) {
            $column = collect($columns)->firstWhere('name', $columnName);
            $this->assertNotNull($column, "Column {$columnName} does not exist.");
            $this->assertInstanceOf(TextColumn::class, $column, "Column {$columnName} is not of type TextColumn.");
        }
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
