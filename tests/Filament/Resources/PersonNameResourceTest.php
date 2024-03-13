<?php

namespace Tests\Filament\Resources;

use Tests\TestCase;
use App\Models\PersonName;
use App\Filament\Resources\PersonNameResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Form;
use Filament\Tables\Table;

class PersonNameResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_schema_is_correct()
    {
        $form = PersonNameResource::form(Form::make())->getSchema();

        $this->assertCount(10, $form);
        foreach ($form as $index => $component) {
            $this->assertInstanceOf(TextInput::class, $component);
            $expectedName = match ($index) {
                0 => 'group',
                1 => 'gid',
                2 => 'name',
                3 => 'type',
                4 => 'npfx',
                5 => 'givn',
                6 => 'nick',
                7 => 'spfx',
                8 => 'surn',
                9 => 'nsfx',
            };
            $this->assertEquals($expectedName, $component->getName());
        }
    }

    public function test_table_columns_are_correct()
    {
        $table = PersonNameResource::table(Table::make())->getColumns();

        $this->assertCount(12, $table);
        foreach ($table as $index => $column) {
            $this->assertInstanceOf(TextColumn::class, $column);
            $expectedName = match ($index) {
                0 => 'group',
                1 => 'gid',
                2 => 'name',
                3 => 'type',
                4 => 'npfx',
                5 => 'givn',
                6 => 'nick',
                7 => 'spfx',
                8 => 'surn',
                9 => 'nsfx',
                10 => 'created_at',
                11 => 'updated_at',
            };
            $this->assertEquals($expectedName, $column->getName());
        }
    }

    public function test_index_route()
    {
        $response = $this->get(route('filament.resources.person-names.index'));
        $response->assertStatus(200);
        $response->assertViewIs('filament.resources.person-names.pages.list-person-names');
    }

    public function test_create_route()
    {
        $response = $this->get(route('filament.resources.person-names.create'));
        $response->assertStatus(200);
        $response->assertViewIs('filament.resources.person-names.pages.create-person-name');
    }

    public function test_edit_route()
    {
        $personName = PersonName::factory()->create();
        $response = $this->get(route('filament.resources.person-names.edit', $personName));
        $response->assertStatus(200);
        $response->assertViewIs('filament.resources.person-names.pages.edit-person-name');
    }
}
