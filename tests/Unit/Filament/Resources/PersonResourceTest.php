<?php

namespace Tests\Unit\Filament\Resources;

use App\Filament\Resources\PersonResource;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function form_schema_is_correctly_defined()
    {
        $form = PersonResource::form(app(\Filament\Forms\Form::class));
        $schema = collect($form->getSchema());

        // Example assertion for a 'name' field
        $nameField = $schema->firstWhere('name', 'name');
        $this->assertInstanceOf(TextInput::class, $nameField);
        $this->assertTrue($nameField->isRequired());
        $this->assertEquals(255, $nameField->getMaxLength());

        // Add more assertions for other fields as per the actual schema of PersonResource
    }

    /** @test */
    public function table_columns_and_actions_are_correctly_defined()
    {
        $table = PersonResource::table(app(\Filament\Tables\Table::class));
        $columns = collect($table->getColumns());

        // Example assertion for a 'name' column
        $this->assertTrue($columns->pluck('name')->contains('name'));
        // Add more assertions for other columns as per the actual schema of PersonResource

        $actions = collect($table->getActions());
        $this->assertTrue($actions->pluck('name')->contains(EditAction::class));

        $bulkActions = collect($table->getBulkActions());
        $this->assertTrue($bulkActions->pluck('name')->contains(DeleteBulkAction::class));
    }
}
