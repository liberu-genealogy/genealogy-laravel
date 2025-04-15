<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\PersonNameRomnResource;
use App\Models\PersonNameRomn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonNameRomnResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_schema_is_correct(): void
    {
        $form = PersonNameRomnResource::form(Form::make())->getSchema();

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

    public function test_table_columns_are_correct(): void
    {
        $table = PersonNameRomnResource::table(Table::make())->getColumns();

        $this->assertCount(12, $table);
        foreach ($table as $index => $column) {
            $this->assertInstanceOf(TextColumn::class, $column);
            $expectedName = match ($index) {
                0  => 'group',
                1  => 'gid',
                2  => 'name',
                3  => 'type',
                4  => 'npfx',
                5  => 'givn',
                6  => 'nick',
                7  => 'spfx',
                8  => 'surn',
                9  => 'nsfx',
                10 => 'created_at',
                11 => 'updated_at',
            };
            $this->assertEquals($expectedName, $column->getName());
        }
    }

    public function test_index_route(): void
    {
        $response = $this->get(route('filament.resources.person-name-romns.index'));
        $response->assertStatus(200);
        $response->assertViewIs('filament.resources.person-name-romns.pages.list-person-name-romns');
    }

    public function test_create_route(): void
    {
        $response = $this->get(route('filament.resources.person-name-romns.create'));
        $response->assertStatus(200);
        $response->assertViewIs('filament.resources.person-name-romns.pages.create-person-name-romn');
    }

    public function test_edit_route(): void
    {
        $personNameRomn = PersonNameRomn::factory()->create();
        $response = $this->get(route('filament.resources.person-name-romns.edit', $personNameRomn));
        $response->assertStatus(200);
        $response->assertViewIs('filament.resources.person-name-romns.pages.edit-person-name-romn');
    }
}
