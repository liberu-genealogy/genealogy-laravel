<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\DnaMatchingResource;
use App\Models\DnaMatching;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase; // Change from PHPUnit\Framework\TestCase;

class DnaMatchingResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_schema_includes_all_fields_with_correct_configurations(): void
    {
        $formFields = DnaMatchingResource::form([])->getSchema();

        $this->assertArrayHasKey('user_id', $formFields);
        $this->assertTrue($formFields['user_id']->isRequired());
        $this->assertTrue($formFields['user_id']->isNumeric());

        $this->assertArrayHasKey('image', $formFields);
        $this->assertTrue($formFields['image']->isRequired());

        $fieldsWithMaxLength = ['file1', 'file2', 'total_shared_cm', 'largest_cm_segment', 'match_name'];
        foreach ($fieldsWithMaxLength as $field) {
            $this->assertArrayHasKey($field, $formFields);
            $this->assertEquals(255, $formFields[$field]->getMaxLength());
        }

        $this->assertArrayHasKey('match_id', $formFields);
        $this->assertTrue($formFields['match_id']->isNumeric());
    }

    public function test_table_schema_includes_all_columns_with_correct_configurations(): void
    {
        $tableColumns = DnaMatchingResource::table([])->getColumns();

        $numericAndSortableColumns = ['user_id', 'match_id'];
        foreach ($numericAndSortableColumns as $column) {
            $this->assertTrue($tableColumns[$column]->isNumeric());
            $this->assertTrue($tableColumns[$column]->isSortable());
        }

        $searchableColumns = ['file1', 'file2', 'total_shared_cm', 'largest_cm_segment', 'match_name'];
        foreach ($searchableColumns as $column) {
            $this->assertTrue($tableColumns[$column]->isSearchable());
        }

        $sortableColumns = ['created_at', 'updated_at'];
        foreach ($sortableColumns as $column) {
            $this->assertTrue($tableColumns[$column]->isSortable());
        }
    }

    public function test_crud_operations(): void
    {
        $dnaMatchingData = [
            'user_id'            => 1,
            'image'              => 'test_image.png',
            'file1'              => 'file1.txt',
            'file2'              => 'file2.txt',
            'total_shared_cm'    => '100',
            'largest_cm_segment' => '50',
            'match_id'           => 2,
            'match_name'         => 'Test Match',
        ];

        $dnaMatching = DnaMatching::create($dnaMatchingData);
        $this->assertDatabaseHas('dna_matchings', $dnaMatchingData);

        $retrievedDnaMatching = DnaMatching::find($dnaMatching->id);
        $this->assertNotNull($retrievedDnaMatching);

        $updatedData = ['match_name' => 'Updated Test Match'];
        $dnaMatching->update($updatedData);
        $this->assertDatabaseHas('dna_matchings', array_merge($dnaMatchingData, $updatedData));

        $dnaMatching->delete();
        $this->assertDatabaseMissing('dna_matchings', ['id' => $dnaMatching->id]);
    }
}
