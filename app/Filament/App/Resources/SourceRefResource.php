<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\SourceRefResource\Pages\ListSourceRefs;
use App\Filament\App\Resources\SourceRefResource\Pages\CreateSourceRef;
use App\Filament\App\Resources\SourceRefResource\Pages\EditSourceRef;
use BackedEnum;
use App\Filament\App\Resources\SourceRefResource\Pages;
use App\Models\SourceRef;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class SourceRefResource extends Resource
{
    protected static ?string $model = SourceRef::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = '🔍 Research & Analysis';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->maxLength(255),
                TextInput::make('gid')
                    ->numeric(),
                TextInput::make('sour_id')
                    ->numeric(),
                TextInput::make('text')
                    ->maxLength(255),
                TextInput::make('quay')
                    ->maxLength(255),
                TextInput::make('page')
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
                TextColumn::make('sour_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('text')
                    ->searchable(),
                TextColumn::make('quay')
                    ->searchable(),
                TextColumn::make('page')
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
            'index'  => ListSourceRefs::route('/'),
            'create' => CreateSourceRef::route('/create'),
            'edit'   => EditSourceRef::route('/{record}/edit'),
        ];
    }
}
