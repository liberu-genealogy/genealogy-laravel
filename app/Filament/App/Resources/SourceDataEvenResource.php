<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\SourceDataEvenResource\Pages\ListSourceDataEvens;
use App\Filament\App\Resources\SourceDataEvenResource\Pages\CreateSourceDataEven;
use App\Filament\App\Resources\SourceDataEvenResource\Pages\EditSourceDataEven;
use BackedEnum;
use App\Filament\App\Resources\SourceDataEvenResource\Pages;
use App\Models\SourceDataEven;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class SourceDataEvenResource extends Resource
{
    protected static ?string $model = SourceDataEven::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = '\ud83d\udd0d Research & Analysis';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->maxLength(255),
                TextInput::make('gid')
                    ->maxLength(255),
                TextInput::make('date')
                    ->maxLength(255),
                TextInput::make('plac')
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
                    ->searchable(),
                TextColumn::make('date')
                    ->searchable(),
                TextColumn::make('plac')
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
            'index'  => ListSourceDataEvens::route('/'),
            'create' => CreateSourceDataEven::route('/create'),
            'edit'   => EditSourceDataEven::route('/{record}/edit'),
        ];
    }
}
