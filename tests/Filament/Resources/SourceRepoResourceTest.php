<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\SourceRepoResource;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceRepoResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_schema_contains_all_fields_with_correct_configurations()
    {
        $form = SourceRepoResource::form(app(Forms\Form::class));

        $this->assertTrue($form->hasComponent('group'));
        $this->assertInstanceOf(TextInput::class, $form->getComponent('group'));
        $this->assertTrue($form->getComponent('group')->isRequired());
        $this->assertEquals(255, $form->getComponent('group')->getMaxLength());

        $this->assertTrue($form->hasComponent('gid'));
        $this->assertInstanceOf(TextInput::class, $form->getComponent('gid'));
        $this->assertTrue($form->getComponent('gid')->isRequired());
        $this->assertTrue($form->getComponent('gid')->isNumeric());

        $this->assertTrue($form->hasComponent('repo_id'));
        $this->assertInstanceOf(TextInput::class, $form->getComponent('repo_id'));
        $this->assertTrue($form->getComponent('repo_id')->isRequired());
        $this->assertEquals(255, $form->getComponent('repo_id')->getMaxLength());

        $this->assertTrue($form->hasComponent('caln'));
        $this->assertInstanceOf(Textarea::class, $form->getComponent('caln'));
        $this->assertTrue($form->getComponent('caln')->isRequired());
        $this->assertEquals(65535, $form->getComponent('caln')->getMaxLength());
    }

    public function test_table_configuration_defines_all_columns_correctly()
    {
        $table = SourceRepoResource::table(app(Tables\Table::class));

        $columns = ['group', 'gid', 'repo_id', 'created_at', 'updated_at'];
        foreach ($columns as $columnName) {
            $this->assertTrue($table->hasColumn($columnName));
        }

        $this->assertTrue($table->getColumn('group')->isSearchable());
        $this->assertTrue($table->getColumn('gid')->isNumeric());
        $this->assertTrue($table->getColumn('gid')->isSortable());
        $this->assertTrue($table->getColumn('repo_id')->isSearchable());
        $this->assertTrue($table->getColumn('created_at')->isDateTime());
        $this->assertTrue($table->getColumn('created_at')->isSortable());
        $this->assertTrue($table->getColumn('updated_at')->isDateTime());
        $this->assertTrue($table->getColumn('updated_at')->isSortable());
    }
}
