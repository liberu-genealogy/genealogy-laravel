<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\FamilyResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamilyResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_fields_configuration()
    {
        $form = FamilyResource::form(Form::make())->getSchema();

        $expectedFields = [
            'description' => ['type' => 'textarea', 'maxLength' => 65535],
            'is_active'   => ['type' => 'numeric'],
            'type_id'     => ['type' => 'numeric'],
            'husband_id'  => ['type' => 'numeric'],
            'wife_id'     => ['type' => 'numeric'],
            'chan'        => ['type' => 'text', 'maxLength' => 255],
            'nchi'        => ['type' => 'text', 'maxLength' => 255],
            'rin'         => ['type' => 'text', 'maxLength' => 255],
        ];

        foreach ($expectedFields as $fieldName => $details) {
            $field = $form->getComponent($fieldName);
            $this->assertNotNull($field, "{$fieldName} field is not defined.");
            $this->assertEquals($details['type'], $field->getComponentType(), "{$fieldName} field should be of type {$details['type']}.");
            if (isset($details['maxLength'])) {
                $this->assertEquals($details['maxLength'], $field->getMaxLength(), "{$fieldName} field maxLength should be {$details['maxLength']}.");
            }
        }
    }

    public function test_table_configuration()
    {
        $table = FamilyResource::table(Table::make())->getColumns();

        $expectedColumns = [
            'is_active'  => ['sortable' => true, 'type' => 'numeric'],
            'type_id'    => ['sortable' => true, 'type' => 'numeric'],
            'husband_id' => ['sortable' => true, 'type' => 'numeric'],
            'wife_id'    => ['sortable' => true, 'type' => 'numeric'],
            'created_at' => ['sortable' => true, 'type' => 'dateTime'],
            'updated_at' => ['sortable' => true, 'type' => 'dateTime'],
            'deleted_at' => ['sortable' => true, 'type' => 'dateTime'],
            'chan'       => ['searchable' => true, 'type' => 'text'],
            'nchi'       => ['searchable' => true, 'type' => 'text'],
            'rin'        => ['searchable' => true, 'type' => 'text'],
        ];

        foreach ($expectedColumns as $columnName => $details) {
            $column = $table->firstWhere('name', $columnName);
            $this->assertNotNull($column, "{$columnName} column is not defined.");
            foreach ($details as $property => $value) {
                $this->assertEquals($value, $column->{$property}, "{$columnName} column {$property} should be {$value}.");
            }
        }
    }
}
