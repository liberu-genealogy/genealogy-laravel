<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\SourceRefResource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form; // Add this import
use Filament\Tables\Table; // Add this import
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceRefResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_schema_contains_all_fields_with_correct_configurations(): void
    {
        $form = SourceRefResource::form(app(Form::class));

        $this->assertTrue($form->hasComponent('group'));
        $this->assertInstanceOf(TextInput::class, $form->getComponent('group'));
        $this->assertEquals(255, $form->getComponent('group')->getMaxLength());

        $this->assertTrue($form->hasComponent('gid'));
        $this->assertInstanceOf(TextInput::class, $form->getComponent('gid'));
        $this->assertTrue($form->getComponent('gid')->isNumeric());

        $this->assertTrue($form->hasComponent('sour_id'));
        $this->assertInstanceOf(TextInput::class, $form->getComponent('sour_id'));
        $this->assertTrue($form->getComponent('sour_id')->isNumeric());

        $this->assertTrue($form->hasComponent('text'));
        $this->assertInstanceOf(TextInput::class, $form->getComponent('text'));
        $this->assertEquals(255, $form->getComponent('text')->getMaxLength());

        $this->assertTrue($form->hasComponent('quay'));
        $this->assertInstanceOf(TextInput::class, $form->getComponent('quay'));
        $this->assertEquals(255, $form->getComponent('quay')->getMaxLength());

        $this->assertTrue($form->hasComponent('page'));
        $this->assertInstanceOf(TextInput::class, $form->getComponent('page'));
        $this->assertEquals(255, $form->getComponent('page')->getMaxLength());
    }

    public function test_table_configuration_defines_all_columns_correctly(): void
    {
        $table = SourceRefResource::table(app(Table::class));

        $columns = ['group', 'gid', 'sour_id', 'text', 'quay', 'page', 'created_at', 'updated_at'];
        foreach ($columns as $columnName) {
            $this->assertTrue($table->hasColumn($columnName));
        }

        $this->assertTrue($table->getColumn('group')->isSearchable());
        $this->assertTrue($table->getColumn('gid')->isNumeric());
        $this->assertTrue($table->getColumn('gid')->isSortable());
        $this->assertTrue($table->getColumn('sour_id')->isNumeric());
        $this->assertTrue($table->getColumn('sour_id')->isSortable());
        $this->assertTrue($table->getColumn('text')->isSearchable());
        $this->assertTrue($table->getColumn('quay')->isSearchable());
        $this->assertTrue($table->getColumn('page')->isSearchable());
        $this->assertTrue($table->getColumn('created_at')->isDateTime());
        $this->assertTrue($table->getColumn('created_at')->isSortable());
        $this->assertTrue($table->getColumn('updated_at')->isDateTime());
        $this->assertTrue($table->getColumn('updated_at')->isSortable());
    }
}
