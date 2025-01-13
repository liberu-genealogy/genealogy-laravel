

<?php

namespace Tests\Unit\Filament\Resources;

use App\Filament\App\Resources\RepositoryResource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use PHPUnit\Framework\TestCase;

class RepositoryResourceTest extends TestCase
{
    public function test_form_schema_contains_expected_fields(): void
    {
        $form = RepositoryResource::form(null);
        $schema = $form->getSchema();

        $expectedFields = [
            'group' => TextInput::class,
            'gid' => TextInput::class,
            'name' => TextInput::class,
            'description' => Textarea::class,
            'date' => DateTimePicker::class,
            'is_active' => TextInput::class,
            'type_id' => TextInput::class,
            'repo' => TextInput::class,
            'addr_id' => TextInput::class,
            'rin' => TextInput::class,
            'phon' => TextInput::class,
            'email' => TextInput::class,
            'fax' => TextInput::class,
            'www' => TextInput::class
        ];

        foreach ($expectedFields as $fieldName => $fieldClass) {
            $field = collect($schema)->firstWhere('name', $fieldName);
            $this->assertNotNull($field, "Field {$fieldName} does not exist.");
            $this->assertInstanceOf($fieldClass, $field, "Field {$fieldName} is not of type {$fieldClass}.");
        }
    }

    public function test_table_configuration_includes_correct_columns_and_actions(): void
    {
        $table = RepositoryResource::table(null);
        $columns = $table->getColumns();
        $actions = $table->getActions();
        $bulkActions = $table->getBulkActions();

        $expectedColumns = [
            'group', 'gid', 'name', 'date', 'is_active', 'type_id', 'repo', 
            'addr_id', 'rin', 'phon', 'email', 'fax', 'www', 'created_at', 'updated_at'
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