<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\AddrResource;
use App\Models\Addr;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase; // Change from PHPUnit\Framework\TestCase;

class AddrResourceTest extends TestCase
{
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
