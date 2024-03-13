<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\Resources\PersonSubmResource;
use App\Models\PersonSubm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonSubmResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_association()
    {
        $this->assertEquals(PersonSubm::class, PersonSubmResource::getModel());
    }

    public function test_form_fields()
    {
        $formFields = PersonSubmResource::form([])->getSchema();

        $this->assertArrayHasKey('group', $formFields);
        $this->assertEquals(255, $formFields['group']->getMaxLength());

        $this->assertArrayHasKey('gid', $formFields);
        $this->assertTrue($formFields['gid']->isNumeric());

        $this->assertArrayHasKey('subm', $formFields);
        $this->assertEquals(255, $formFields['subm']->getMaxLength());
    }

    public function test_table_columns()
    {
        $tableColumns = PersonSubmResource::table([])->getColumns();

        $this->assertArrayHasKey('group', $tableColumns);
        $this->assertTrue($tableColumns['group']->isSearchable());

        $this->assertArrayHasKey('gid', $tableColumns);
        $this->assertTrue($tableColumns['gid']->isSortable());

        $this->assertArrayHasKey('subm', $tableColumns);
        $this->assertTrue($tableColumns['subm']->isSearchable());

        $this->assertArrayHasKey('created_at', $tableColumns);
        $this->assertTrue($tableColumns['created_at']->isSortable());
        $this->assertTrue($tableColumns['created_at']->isToggleable());

        $this->assertArrayHasKey('updated_at', $tableColumns);
        $this->assertTrue($tableColumns['updated_at']->isSortable());
        $this->assertTrue($tableColumns['updated_at']->isToggleable());
    }
}
