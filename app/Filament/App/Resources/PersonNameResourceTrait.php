<?php

namespace App\Filament\App\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

trait PersonNameResourceTrait
{
    public static function baseForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')->maxLength(255),
                TextInput::make('gid')->numeric(),
                TextInput::make('name')->maxLength(255),
                TextInput::make('type')->maxLength(255),
                TextInput::make('npfx')->maxLength(255),
                TextInput::make('givn')->maxLength(255),
                TextInput::make('nick')->maxLength(255),
                TextInput::make('spfx')->maxLength(255),
                TextInput::make('surn')->maxLength(255),
                TextInput::make('nsfx')->maxLength(255),
            ]);
    }

    public static function baseTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')->searchable(),
                TextColumn::make('gid')->numeric()->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('type')->searchable(),
                TextColumn::make('npfx')->searchable(),
                TextColumn::make('givn')->searchable(),
                TextColumn::make('nick')->searchable(),
                TextColumn::make('spfx')->searchable(),
                TextColumn::make('surn')->searchable(),
                TextColumn::make('nsfx')->searchable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
