&lt;?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\FamilyEventResource;
use App\Models\FamilyEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamilyEventResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_fields_configuration()
    {
        $form = FamilyEventResource::form(Form::make())->getSchema();

        $expectedFields = [
            'family_id', 'places_id', 'date', 'title', 'description',
            'converted_date', 'year', 'month', 'day', 'type', 'plac',
            'addr_id', 'phon', 'caus', 'age', 'agnc', 'husb', 'wife',
        ];

        foreach ($expectedFields as $field) {
            $this->assertNotNull($form->getComponent($field), "{$field} field is not defined.");
        }

        // Example of asserting specific field properties
        $this->assertTrue($form->getComponent('family_id')->isRequired(), "family_id field should be required.");
        $this->assertEquals(65535, $form->getComponent('description')->getMaxLength(), "description field maxLength should be 65535.");
    }

    public function test_table_configuration()
    {
        $table = FamilyEventResource::table(Table::make())->getColumns();

        $expectedColumns = [
            'family_id', 'places_id', 'title', 'converted_date', 'created_at',
            'updated_at', 'deleted_at', 'year', 'month', 'day', 'type', 'plac',
            'addr_id', 'phon', 'age', 'agnc', 'husb', 'wife',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertNotNull($table[$column], "{$column} column is not defined.");
        }

        // Example of asserting specific column properties
        $this->assertTrue($table['family_id']->isSortable(), "family_id column should be sortable.");
        $this->assertTrue($table['title']->isSearchable(), "title column should be searchable.");
    }
}
