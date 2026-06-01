<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\SourceResource;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_schema_contains_all_fields_with_correct_configurations(): void
    {
        $form = SourceResource::form(app(\Filament\Forms\Form::class));

        $fields = [
            'name'           => TextInput::class,
            'description'    => Textarea::class,
            'date'           => TextInput::class,
            'is_active'      => TextInput::class,
            'author_id'      => TextInput::class,
            'repository_id'  => TextInput::class,
            'publication_id' => TextInput::class,
            'type_id'        => TextInput::class,
            'sour'           => TextInput::class,
            'titl'           => Textarea::class,
            'auth'           => TextInput::class,
            'data'           => TextInput::class,
            'text'           => Textarea::class,
            'publ'           => Textarea::class,
            'abbr'           => TextInput::class,
            'group'          => TextInput::class,
            'gid'            => TextInput::class,
            'quay'           => TextInput::class,
            'rin'            => TextInput::class,
            'note'           => TextInput::class,
        ];

        foreach ($fields as $fieldName => $fieldType) {
            $this->assertTrue($form->hasComponent($fieldName));
            $this->assertInstanceOf($fieldType, $form->getComponent($fieldName));
        }
    }

    public function test_table_configuration_defines_all_columns_correctly(): void
    {
        $table = SourceResource::table(app(\Filament\Tables\Table::class));

        $columns = [
            'name', 'date', 'is_active', 'author_id', 'repository_id', 'publication_id', 'type_id', 'sour', 'auth', 'data', 'abbr', 'group', 'gid', 'quay', 'rin', 'note', 'created_at', 'updated_at',
        ];

        foreach ($columns as $columnName) {
            $this->assertTrue($table->hasColumn($columnName));
        }
    }
}
