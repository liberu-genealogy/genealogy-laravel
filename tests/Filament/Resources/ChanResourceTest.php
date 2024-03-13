<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\ChanResource;
use App\Models\Chan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class ChanResourceTest extends TestCase
{
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
    {
        $chanData = [
            'group' => 'Test Group',
            'gid' => 123,
            'date' => '2023-01-01',
            'time' => '12:00:00',
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
