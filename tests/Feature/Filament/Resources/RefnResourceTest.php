<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\Resources\RefnResource;
use App\Models\Refn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefnResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_association()
    {
        $this->assertEquals(Refn::class, RefnResource::getModel());
    }

    public function test_form_fields()
    {
        $formFields = RefnResource::form([])->getSchema();

        $groupField = collect($formFields)->firstWhere('name', 'group');
        $this->assertNotNull($groupField);
        $this->assertEquals(255, $groupField->getMaxLength());

        $gidField = collect($formFields)->firstWhere('name', 'gid');
        $this->assertNotNull($gidField);
        $this->assertTrue($gidField->isNumeric());

        $refnField = collect($formFields)->firstWhere('name', 'refn');
        $this->assertNotNull($refnField);
        $this->assertEquals(255, $refnField->getMaxLength());

        $typeField = collect($formFields)->firstWhere('name', 'type');
        $this->assertNotNull($typeField);
        $this->assertEquals(255, $typeField->getMaxLength());
    }

    public function test_table_columns()
    {
        $tableColumns = RefnResource::table([])->getColumns();

        $this->assertTrue(collect($tableColumns)->firstWhere('name', 'group')->isSearchable());
        $this->assertTrue(collect($tableColumns)->firstWhere('name', 'gid')->isNumeric()->isSortable());
        $this->assertTrue(collect($tableColumns)->firstWhere('name', 'refn')->isSearchable());
        $this->assertTrue(collect($tableColumns)->firstWhere('name', 'type')->isSearchable());
        $this->assertTrue(collect($tableColumns)->firstWhere('name', 'created_at')->isSortable()->isToggleable());
        $this->assertTrue(collect($tableColumns)->firstWhere('name', 'updated_at')->isSortable()->isToggleable());
    }
}
