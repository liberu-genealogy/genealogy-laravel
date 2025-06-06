<?php

namespace App\Filament\App\Resources;

use BackedEnum;
use App\Filament\App\Resources\PersonNameFoneResource\Pages;
use App\Models\PersonNameFone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class PersonNameFoneResource extends Resource
{
    protected static ?string $model = PersonNameFone::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    #[\Override]
    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('group')
                    ->maxLength(255),
                Forms\Components\TextInput::make('gid')
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('npfx')
                    ->maxLength(255),
                Forms\Components\TextInput::make('givn')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nick')
                    ->maxLength(255),
                Forms\Components\TextInput::make('spfx')
                    ->maxLength(255),
                Forms\Components\TextInput::make('surn')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nsfx')
                    ->maxLength(255),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gid')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('npfx')
                    ->searchable(),
                Tables\Columns\TextColumn::make('givn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nick')
                    ->searchable(),
                Tables\Columns\TextColumn::make('spfx')
                    ->searchable(),
                Tables\Columns\TextColumn::make('surn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nsfx')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPersonNameFones::route('/'),
            'create' => Pages\CreatePersonNameFone::route('/create'),
            'edit'   => Pages\EditPersonNameFone::route('/{record}/edit'),
        ];
    }
}
