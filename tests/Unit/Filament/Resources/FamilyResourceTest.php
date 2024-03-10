<?php

namespace Tests\Unit\Filament\Resources\Unit\Filament\Resources;

use App\Filament\Resources\FamilyResource;
use App\Models\Family;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamilyResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_schema_is_correctly_defined()
    {
        $form = app(\Filament\Forms\Form::class);
        $schema = $form->getSchema();

        $descriptionField = $schema->firstWhere('name', 'description');
        $this->assertTrue($descriptionField instanceof Textarea);
        $this->assertEquals(65535, $descriptionField->maxLength);

        $isActiveField = $schema->firstWhere('name', 'is_active');
        $this->assertTrue($isActiveField instanceof TextInput);
        $this->assertTrue($isActiveField->rules->contains('numeric'));

        $typeIdField = $schema->firstWhere('name', 'type_id');
        $this->assertTrue($typeIdField instanceof TextInput);
        $this->assertTrue($typeIdField->getRule('numeric') !== null);

        // Repeat for other fields: husband_id, wife_id, chan, nchi, rin
    }

    public function test_table_columns_and_actions_are_correctly_defined()
    {
        $table = FamilyResource::table(app(\Filament\Tables\Table::class));
        $columns = collect($table->getColumns());

        $this->assertTrue($columns->pluck('name')->contains('is_active'));
        $this->assertTrue($columns->pluck('name')->contains('type_id'));
        $this->assertTrue($columns->pluck('name')->contains('husband_id'));
        $this->assertTrue($columns->pluck('name')->contains('wife_id'));
        $this->assertTrue($columns->pluck('name')->contains('created_at'));
        $this->assertTrue($columns->pluck('name')->contains('updated_at'));
        $this->assertTrue($columns->pluck('name')->contains('deleted_at'));
        $this->assertTrue($columns->pluck('name')->contains('chan'));
        $this->assertTrue($columns->pluck('name')->contains('nchi'));
        $this->assertTrue($columns->pluck('name')->contains('rin'));

        $actions = collect($table->getActions());
        $this->assertTrue($actions->pluck('name')->contains(EditAction::class));

        $bulkActions = collect($table->getBulkActions());
        $this->assertTrue($bulkActions->pluck('name')->contains(DeleteBulkAction::class));
    }
}
