<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\MediaObjectResource\Pages\ListMediaObjects;
use App\Filament\App\Resources\MediaObjectResource\Pages\CreateMediaObject;
use App\Filament\App\Resources\MediaObjectResource\Pages\EditMediaObject;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\MediaObjectResource\Pages;
use App\Models\MediaObject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class MediaObjectResource extends Resource
{
    protected static ?string $model = MediaObject::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Photos & Documents';

    protected static string | \UnitEnum | null $navigationGroup = '\ud83d\udcc1 Media & Documents';

    protected static ?int $navigationSort = 1;

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
                ->components([
                    TextInput::make('gid')
                        ->numeric(),
                    TextInput::make('group')
                        ->maxLength(255),
                    TextInput::make('titl')
                        ->maxLength(255),
                    TextInput::make('obje_id')
                        ->maxLength(255),
                    TextInput::make('rin')
                        ->maxLength(255),
                ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gid')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('group')
                    ->searchable(),
                TextColumn::make('titl')
                    ->searchable(),
                TextColumn::make('obje_id')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rin')
                    ->searchable(),
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
            'index'  => ListMediaObjects::route('/'),
            'create' => CreateMediaObject::route('/create'),
            'edit'   => EditMediaObject::route('/{record}/edit'),
        ];
    }
}
