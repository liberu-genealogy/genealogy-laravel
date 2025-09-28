<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\PersonNameResource\Pages\ListPersonNames;
use App\Filament\App\Resources\PersonNameResource\Pages\CreatePersonName;
use App\Filament\App\Resources\PersonNameResource\Pages\EditPersonName;
use BackedEnum;
use App\Filament\App\Resources\PersonNameResource\Pages;
use App\Models\PersonName;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class PersonNameResource extends Resource
{
    protected static ?string $model = PersonName::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = '\ud83d\udc65 Family Tree';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->maxLength(255),
                TextInput::make('gid')
                    ->numeric(),
                TextInput::make('name')
                    ->maxLength(255),
                TextInput::make('type')
                    ->maxLength(255),
                TextInput::make('npfx')
                    ->maxLength(255),
                TextInput::make('givn')
                    ->maxLength(255),
                TextInput::make('nick')
                    ->maxLength(255),
                TextInput::make('spfx')
                    ->maxLength(255),
                TextInput::make('surn')
                    ->maxLength(255),
                TextInput::make('nsfx')
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
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('npfx')
                    ->searchable(),
                TextColumn::make('givn')
                    ->searchable(),
                TextColumn::make('nick')
                    ->searchable(),
                TextColumn::make('spfx')
                    ->searchable(),
                TextColumn::make('surn')
                    ->searchable(),
                TextColumn::make('nsfx')
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
            'index'  => ListPersonNames::route('/'),
            'create' => CreatePersonName::route('/create'),
            'edit'   => EditPersonName::route('/{record}/edit'),
        ];
    }
}
