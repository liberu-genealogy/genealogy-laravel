<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\PersonLdsResource\Pages\ListPersonLds;
use App\Filament\App\Resources\PersonLdsResource\Pages\CreatePersonLds;
use App\Filament\App\Resources\PersonLdsResource\Pages\EditPersonLds;
use BackedEnum;
use App\Filament\App\Resources\PersonLdsResource\Pages;
use App\Models\PersonLds;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class PersonLdsResource extends Resource
{
    protected static ?string $model = PersonLds::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->maxLength(255),
                TextInput::make('gid')
                    ->numeric(),
                TextInput::make('type')
                    ->maxLength(255),
                TextInput::make('stat')
                    ->maxLength(255),
                TextInput::make('date')
                    ->maxLength(255),
                TextInput::make('plac')
                    ->maxLength(255),
                TextInput::make('temp')
                    ->maxLength(255),
                TextInput::make('slac_famc')
                    ->maxLength(255),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->searchable(),
                TextColumn::make('gid')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('stat')
                    ->searchable(),
                TextColumn::make('date')
                    ->searchable(),
                TextColumn::make('plac')
                    ->searchable(),
                TextColumn::make('temp')
                    ->searchable(),
                TextColumn::make('slac_famc')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPersonLds::route('/'),
            'create' => CreatePersonLds::route('/create'),
            'edit'   => EditPersonLds::route('/{record}/edit'),
        ];
    }
}
