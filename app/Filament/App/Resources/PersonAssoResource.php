<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\PersonAssoResource\Pages\ListPersonAssos;
use App\Filament\App\Resources\PersonAssoResource\Pages\CreatePersonAsso;
use App\Filament\App\Resources\PersonAssoResource\Pages\EditPersonAsso;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\PersonAssoResource\Pages;
use App\Models\PersonAsso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class PersonAssoResource extends Resource
{
    protected static ?string $model = PersonAsso::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Person Associations';

    protected static string | \UnitEnum | null $navigationGroup = 'Person';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->maxLength(255),
                TextInput::make('gid')
                    ->numeric(),
                TextInput::make('indi')
                    ->maxLength(255),
                TextInput::make('rela')
                    ->maxLength(255),
                TextInput::make('import_confirm')
                    ->required()
                    ->numeric()
                    ->default(0),
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
                TextColumn::make('indi')
                    ->searchable(),
                TextColumn::make('rela')
                    ->searchable(),
                TextColumn::make('import_confirm')
                    ->numeric()
                    ->sortable(),
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
            'index'  => ListPersonAssos::route('/'),
            'create' => CreatePersonAsso::route('/create'),
            'edit'   => EditPersonAsso::route('/{record}/edit'),
        ];
    }
}
