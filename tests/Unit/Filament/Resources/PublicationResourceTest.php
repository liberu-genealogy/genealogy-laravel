<?php

use App\Filament\Resources\PublicationResource;
use App\Models\Publication;

use function Pest\Laravel\get;

test('PublicationResource form schema is correctly defined', function () {
    $form = PublicationResource::form(app(Filament\Forms\Form::class));
    $schema = collect($form->getSchema());

    $nameField = $schema->firstWhere('name', 'name');
    expect($nameField)->toBeInstanceOf(Filament\Forms\Components\TextInput::class);
    expect($nameField->isRequired())->toBeTrue();
    expect($nameField->getMaxLength())->toEqual(255);

    $descriptionField = $schema->firstWhere('name', 'description');
    expect($descriptionField)->toBeInstanceOf(Filament\Forms\Components\TextInput::class);
    expect($descriptionField->isRequired())->toBeTrue();
    expect($descriptionField->getMaxLength())->toEqual(255);

    $isActiveField = $schema->firstWhere('name', 'is_active');
    expect($isActiveField)->toBeInstanceOf(Filament\Forms\Components\TextInput::class);
    expect($isActiveField->isRequired())->toBeTrue();
    expect($isActiveField->getRule('numeric'))->not()->toBeNull();
});

test('PublicationResource table columns and actions are correctly defined', function () {
    $table = PublicationResource::table(app(Filament\Tables\Table::class));
    $columns = collect($table->getColumns());

    expect($columns->pluck('name'))->toContain(['name', 'description', 'is_active', 'created_at', 'updated_at']);
    expect($columns->firstWhere('name', 'is_active')->isNumeric())->toBeTrue();
    expect($columns->firstWhere('name', 'created_at')->isSortable())->toBeTrue();
    expect($columns->firstWhere('name', 'updated_at')->isSortable())->toBeTrue();

    $actions = collect($table->getActions());
    expect($actions->pluck('name'))->toContain('edit');

    $bulkActions = collect($table->getBulkActions());
    expect($bulkActions->pluck('name'))->toContain('delete');
});

test('PublicationResource interacts with the database correctly', function () {
    $publication = Publication::factory()->create(['name' => 'Test Publication', 'description' => 'Test Description', 'is_active' => 1]);

    get(route('publications.index'))
        ->assertInertia(
            fn ($page) => $page
            ->component('Publications/Index')
            ->has(
                'publications',
                fn ($page) => $page
                ->where('name', 'Test Publication')
                ->where('description', 'Test Description')
                ->where('is_active', 1)
                ->etc()
            )
        );
});
