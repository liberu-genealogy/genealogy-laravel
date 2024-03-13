<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\FamilySlgsResource;
use App\Models\FamilySlgs;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Tables;
use Filament\Resources\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * This file contains tests for the FamilySlgs resource in the genealogy-laravel application.
 */
class FamilySlgsResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests the form fields configuration for the FamilySlgs resource.
     * Ensures that all expected fields are present and correctly configured.
     *
     * @return void
     */
    public function test_form_fields_configuration()
    {
        $form = FamilySlgsResource::form(Form::make())->getSchema();

        $expectedFields = [
            'family_id' => ['type' => 'numeric'],
            'stat' => ['type' => 'text', 'maxLength' => 255],
            'date' => ['type' => 'text', 'maxLength' => 255],
            'plac' => ['type' => 'text', 'maxLength' => 255],
            'temp' => ['type' => 'text', 'maxLength' => 255],
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
        $table = FamilySlgsResource::table(Table::make())->getColumns();

        $expectedColumns = [
            'family_id' => ['sortable' => true, 'type' => 'numeric'],
            'stat' => ['searchable' => true, 'type' => 'text'],
            'date' => ['searchable' => true, 'type' => 'text'],
            'plac' => ['searchable' => true, 'type' => 'text'],
            'temp' => ['searchable' => true, 'type' => 'text'],
            'created_at' => ['sortable' => true, 'type' => 'dateTime', 'toggleable' => true],
            'family_id' => ['sortable' => true, 'type' => 'numeric'],
            'stat' => ['searchable' => true, 'type' => 'text'],
            'date' => ['searchable' => true, 'type' => 'text'],
            'plac' => ['searchable' => true, 'type' => 'text'],
            'temp' => ['searchable' => true, 'type' => 'text'],
            'created_at' => ['sortable' => true, 'type' => 'dateTime', 'toggleable' => true],
            'updated_at' => ['sortable' => true, 'type' => 'dateTime', 'toggleable' => true],
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
