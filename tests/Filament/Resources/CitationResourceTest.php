<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\CitationResource;
use App\Models\Citation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class CitationResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_schema_includes_all_fields_with_correct_configurations()
    {
        $formFields = CitationResource::form([])->getSchema();

        $this->assertArrayHasKey('name', $formFields);
        $this->assertTrue($formFields['name']->isRequired());
        $this->assertEquals(255, $formFields['name']->getMaxLength());

        $this->assertArrayHasKey('description', $formFields);
        $this->assertTrue($formFields['description']->isRequired());
        $this->assertEquals(65535, $formFields['description']->getMaxLength());

        $this->assertArrayHasKey('date', $formFields);

        $numericFields = ['is_active', 'volume', 'page', 'confidence', 'source_id'];
        foreach ($numericFields as $field) {
            $this->assertArrayHasKey($field, $formFields);
            $this->assertTrue($formFields[$field]->isRequired());
            $this->assertTrue($formFields[$field]->isNumeric());
        }
    }

    public function test_table_schema_includes_all_columns_with_correct_configurations()
    {
        $tableColumns = CitationResource::table([])->getColumns();

        $searchableColumns = ['name'];
        foreach ($searchableColumns as $column) {
            $this->assertTrue($tableColumns[$column]->isSearchable());
        }

        $sortableColumns = ['date', 'is_active', 'volume', 'page', 'confidence', 'source_id', 'created_at', 'updated_at'];
        foreach ($sortableColumns as $column) {
            $this->assertTrue($tableColumns[$column]->isSortable());
        }
    }

    public function test_crud_operations()
    {
        $citationData = [
            'name'        => 'Test Citation',
            'description' => 'This is a test citation.',
            'date'        => now(),
            'is_active'   => 1,
            'volume'      => 10,
            'page'        => 100,
            'confidence'  => 5,
            'source_id'   => 1,
        ];

        $citation = Citation::create($citationData);
        $this->assertDatabaseHas('citations', $citationData);

        $retrievedCitation = Citation::find($citation->id);
        $this->assertNotNull($retrievedCitation);

        $updatedData = ['name' => 'Updated Citation'];
        $citation->update($updatedData);
        $this->assertDatabaseHas('citations', array_merge($citationData, $updatedData));

        $citation->delete();
        $this->assertDatabaseMissing('citations', ['id' => $citation->id]);
    }
}
