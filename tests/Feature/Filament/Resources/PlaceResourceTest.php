<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\Resources\PlaceResource;
use App\Models\Place;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaceResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_association()
    {
        $this->assertEquals(Place::class, PlaceResource::getModel());
    }

    public function test_form_fields()
    {
        $formFields = PlaceResource::form([])->getSchema();

        $titleField = collect($formFields)->firstWhere('name', 'title');
        $this->assertNotNull($titleField);
        $this->assertTrue($titleField->isRequired());
        $this->assertEquals(255, $titleField->getMaxLength());

        $dateField = collect($formFields)->firstWhere('name', 'date');
        $this->assertNotNull($dateField);
        $this->assertEquals(255, $dateField->getMaxLength());

        $descriptionField = collect($formFields)->firstWhere('name', 'description');
        $this->assertNotNull($descriptionField);
        $this->assertEquals(65535, $descriptionField->getMaxLength());
        $this->assertTrue($descriptionField->getColumnSpan() === 'full');
    }

    public function test_table_columns()
    {
        $tableColumns = PlaceResource::table([])->getColumns();

        $titleColumn = collect($tableColumns)->firstWhere('name', 'title');
        $this->assertTrue($titleColumn->isSearchable());

        $dateColumn = collect($tableColumns)->firstWhere('name', 'date');
        $this->assertTrue($dateColumn->isSearchable());

        $createdAtColumn = collect($tableColumns)->firstWhere('name', 'created_at');
        $this->assertTrue($createdAtColumn->isSortable());
        $this->assertTrue($createdAtColumn->isToggleable());

        $updatedAtColumn = collect($tableColumns)->firstWhere('name', 'updated_at');
        $this->assertTrue($updatedAtColumn->isSortable());
        $this->assertTrue($updatedAtColumn->isToggleable());
    }
}
