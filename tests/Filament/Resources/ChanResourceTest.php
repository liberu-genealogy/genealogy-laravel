<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\ChanResource;
use App\Models\Chan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class ChanResourceTest extends TestCase
{
/**
 * Tests for the ChanResource class.
 *
 * Focuses on testing the ChanResource form and table schemas, along with CRUD 
 * operations for the Chan model.
 *
 * @package Tests\Filament\Resources
 */
    use RefreshDatabase;

    public function test_form_schema_includes_all_fields_with_correct_configurations()
    {
        $formFields = ChanResource::form([])->getSchema();

        $this->assertArrayHasKey('group', $formFields);
        $this->assertEquals(255, $formFields['group']->getMaxLength());

        $this->assertArrayHasKey('gid', $formFields);
        $this->assertTrue($formFields['gid']->isNumeric());

        $this->assertArrayHasKey('date', $formFields);
        $this->assertEquals(255, $formFields['date']->getMaxLength());

        $this->assertArrayHasKey('time', $formFields);
        $this->assertEquals(255, $formFields['time']->getMaxLength());
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
        $tableColumns = ChanResource::table([])->getColumns();

        $this->assertTrue($tableColumns['group']->isSearchable());
        $this->assertTrue($tableColumns['gid']->isNumeric() && $tableColumns['gid']->isSortable());
        $this->assertTrue($tableColumns['date']->isSearchable());
        $this->assertTrue($tableColumns['time']->isSearchable());
        $this->assertTrue($tableColumns['created_at']->isSortable());
        $this->assertTrue($tableColumns['updated_at']->isSortable());
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
        $chanData = [
            'group' => 'Test Group',
            'gid'   => 123,
            'date'  => '2023-01-01',
            'time'  => '12:00:00',
        ];

        // Create
        $chan = Chan::create($chanData);
        $this->assertDatabaseHas('chans', $chanData);

        // Read
        $retrievedChan = Chan::find($chan->id);
        $this->assertNotNull($retrievedChan);

        // Update
        $updatedData = ['group' => 'Updated Group'];
        $chan->update($updatedData);
        $this->assertDatabaseHas('chans', array_merge($chanData, $updatedData));

        // Delete
        $chan->delete();
        $this->assertDatabaseMissing('chans', ['id' => $chan->id]);
    }
}
/**
 * Tests CRUD operations on the Chan model.
 *
 * Ensures that create, read, update, and delete operations work as expected for the Chan model.
 *
 * @return void
 */
