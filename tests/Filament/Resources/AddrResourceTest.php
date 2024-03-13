<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\AddrResource;
use App\Models\Addr;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class AddrResourceTest extends TestCase
{
/**
 * Tests for the AddrResource class.
 *
 * This class provides a suite of tests for the AddrResource form and table schemas, 
 * as well as CRUD operations on the Addr model.
 *
 * @package Tests\Filament\Resources
 */
    use RefreshDatabase;

    public function test_form_schema_includes_all_fields_with_correct_configurations()
    {
        $formFields = AddrResource::form([])->getSchema();

        $expectedFields = ['adr1', 'adr2', 'city', 'stae', 'post', 'ctry'];
        foreach ($expectedFields as $field) {
            $this->assertArrayHasKey($field, $formFields);
            $this->assertEquals(255, $formFields[$field]->getMaxLength());
        }
    }

    public function test_table_schema_includes_all_columns_with_correct_configurations()
/**
 * Tests the form schema includes all fields with correct configurations.
 *
 * Ensures that all expected fields are present in the form schema with the correct 
 * maximum length and other configurations.
 *
 * @return void
 */
    {
        $tableColumns = AddrResource::table([])->getColumns();

        $expectedColumns = ['adr1', 'adr2', 'city', 'stae', 'post', 'ctry', 'created_at', 'updated_at'];
        foreach ($expectedColumns as $column) {
            $this->assertArrayHasKey($column, $tableColumns);
            if (in_array($column, ['created_at', 'updated_at'])) {
                $this->assertTrue($tableColumns[$column]->isSortable());
            } else {
                $this->assertTrue($tableColumns[$column]->isSearchable());
            }
        }
    }

    public function test_crud_operations()
/**
 * Tests the table schema includes all columns with correct configurations.
 *
 * Verifies that all expected columns are present in the table schema, are searchable or sortable 
 * as appropriate, and have the correct configurations.
 *
 * @return void
 */
    {
        $addrData = [
            'adr1' => '123 Main St',
            'adr2' => 'Suite 100',
            'city' => 'Anytown',
            'stae' => 'CA',
            'post' => '12345',
            'ctry' => 'USA',
        ];

        // Create
        $addr = Addr::create($addrData);
        $this->assertDatabaseHas('addrs', $addrData);

        // Read
        $retrievedAddr = Addr::find($addr->id);
        $this->assertNotNull($retrievedAddr);

        // Update
        $updatedData = ['city' => 'Newtown'];
        $addr->update($updatedData);
        $this->assertDatabaseHas('addrs', array_merge($addrData, $updatedData));

        // Delete
        $addr->delete();
        $this->assertDatabaseMissing('addrs', ['id' => $addr->id]);
    }
}
/**
 * Tests CRUD operations on the Addr model.
 *
 * Ensures that create, read, update, and delete operations work as expected for the Addr model.
 *
 * @return void
 */
