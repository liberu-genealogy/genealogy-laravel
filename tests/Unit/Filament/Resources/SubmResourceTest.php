

<?php

namespace Tests\Unit\Filament\Resources;

use App\Filament\App\Resources\SubmResource;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use PHPUnit\Framework\TestCase;

class SubmResourceTest extends TestCase
{
    public function test_form_schema_contains_expected_fields(): void
    {
        $form = SubmResource::form(null);
        $schema = $form->getSchema();

        $expectedFields = [
            'group', 'gid', 'name', 'addr_id', 'rin', 'rfn', 
            'lang', 'phon', 'email', 'fax', 'www'
        ];

        foreach ($expectedFields as $fieldName) {
            $field = collect($schema)->firstWhere('name', $fieldName);
            $this->assertNotNull($field, "Field {$fieldName} does not exist.");
            $this->assertInstanceOf(TextInput::class, $field, "Field {$fieldName} is not of type TextInput.");
        }
    }

    public function test_table_configuration_includes_correct_columns_and_actions(): void
    {
        $table = SubmResource::table(null);
        $columns = $table->getColumns();
        $actions = $table->getActions();
        $bulkActions = $table->getBulkActions();

        $expectedColumns = [
            'group', 'gid', 'name', 'addr_id', 'rin', 'rfn', 'lang',
            'phon', 'email', 'fax', 'www', 'created_at', 'updated_at'
        ];
        
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