<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\PersonAliaResource\Pages\ListPersonAlias;
use App\Filament\App\Resources\PersonAliaResource\Pages\CreatePersonAlia;
use App\Filament\App\Resources\PersonAliaResource\Pages\EditPersonAlia;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\PersonAliaResource\Pages;
use App\Models\PersonAlia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class PersonAliaResource extends Resource
{
    protected static ?string $model = PersonAlia::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Person Alia';

    protected static string | \UnitEnum | null $navigationGroup = '👥 Family Tree';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                ->maxLength(255),
                TextInput::make('gid')
                    ->numeric(),
                TextInput::make('alia')
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
                TextColumn::make('alia')
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
            'index'  => ListPersonAlias::route('/'),
            'create' => CreatePersonAlia::route('/create'),
            'edit'   => EditPersonAlia::route('/{record}/edit'),
        ];
    }
}
