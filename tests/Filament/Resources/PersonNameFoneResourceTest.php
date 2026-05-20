<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\PersonNameFoneResource;
use App\Models\PersonNameFone;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form; // Add this import
use Filament\Tables\Table; // Add this import
use Filament\Tables\Columns\TextColumn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonNameFoneResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_schema_is_correct(): void
    {
        $form = PersonNameFoneResource::form(Form::make())->getSchema();

        $this->assertCount(10, $form);
        $this->assertInstanceOf(TextInput::class, $form[0]);
        $this->assertEquals('group', $form[0]->getName());
        // Continue assertions for each field...

        // Example for 'gid' field
        $this->assertInstanceOf(TextInput::class, $form[1]);
        $this->assertEquals('gid', $form[1]->getName());
        $this->assertTrue($form[1]->getRules()['numeric']);
        // Continue for all fields...
    }

    public function test_table_columns_are_correct(): void
    {
        $table = PersonNameFoneResource::table(Table::make())->getColumns();

        $this->assertCount(12, $table);
        $this->assertInstanceOf(TextColumn::class, $table[0]);
        $this->assertEquals('group', $table[0]->getName());
        $this->assertTrue($table[0]->isSearchable());
        // Continue assertions for each column...
    }

    public function test_index_route(): void
    {
        $response = $this->get(route('filament.resources.person-name-fones.index'));
        $response->assertStatus(200);
        $response->assertViewIs('filament.resources.person-name-fones.pages.list-person-name-fones');
    }

    public function test_create_route(): void
    {
        $response = $this->get(route('filament.resources.person-name-fones.create'));
        $response->assertStatus(200);
        $response->assertViewIs('filament.resources.person-name-fones.pages.create-person-name-fone');
    }

    public function test_edit_route(): void
    {
        $personNameFone = PersonNameFone::factory()->create();
        $response = $this->get(route('filament.resources.person-name-fones.edit', $personNameFone));
        $response->assertStatus(200);
        $response->assertViewIs('filament.resources.person-name-fones.pages.edit-person-name-fone');
    }
}
