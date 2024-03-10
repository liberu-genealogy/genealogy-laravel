<?php

namespace Tests\Unit\Filament\Resources;

use App\Filament\Resources\FamilyResource;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamilyResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_schema_is_correctly_defined()
    {
        $form = FamilyResource::form(app(\Filament\Forms\Form::class));
        $schema = collect($form->getSchema());

        $descriptionField = $schema->firstWhere('name', 'description');
        $this->assertInstanceOf(Textarea::class, $descriptionField);
        $this->assertEquals(65535, $descriptionField->getMaxLength());

        $isActiveField = $schema->firstWhere('name', 'is_active');
        $this->assertInstanceOf(TextInput::class, $isActiveField);
        $this->assertTrue($isActiveField->getRule('numeric') !== null);

        $typeIdField = $schema->firstWhere('name', 'type_id');
        $this->assertInstanceOf(TextInput::class, $typeIdField);
        $this->assertTrue($typeIdField->getRule('numeric') !== null);

        // Repeat for other fields: husband_id, wife_id, chan, nchi, rin
    }

    public function test_table_columns_and_actions_are_correctly_defined()
    {
        $table = FamilyResource::table(app(\Filament\Tables\Table::class));
        $columns = collect($table->getColumns());

        $this->assertTrue($columns->pluck('name')->contains('is_active'));
        $this->assertTrue($columns->pluck('name')->contains('type_id'));
        // Repeat for other columns: husband_id, wife_id, chan, nchi, rin, created_at, updated_at, deleted_at

        $actions = collect($table->getActions());
        $this->assertTrue($actions->pluck('name')->contains(EditAction::class));

        $bulkActions = collect($table->getBulkActions());
        $this->assertTrue($bulkActions->pluck('name')->contains(DeleteBulkAction::class));
    }
}
