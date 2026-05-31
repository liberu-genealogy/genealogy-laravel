<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\SourceDataResource\Pages\ListSourceData;
use App\Filament\App\Resources\SourceDataResource\Pages\CreateSourceData;
use App\Filament\App\Resources\SourceDataResource\Pages\EditSourceData;
use BackedEnum;
use App\Filament\App\Resources\SourceDataResource\Pages;
use App\Models\SourceData;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\App\Resources\AppResource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class SourceDataResource extends AppResource
{
    #[\Override]
    protected static ?string $model = SourceData::class;

    #[\Override]
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-circle-stack';
    #[\Override]
    protected static ?string $navigationLabel = 'Source Data';
    #[\Override]
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
                TextInput::make('date')
                    ->maxLength(255),
                TextInput::make('text')
                    ->maxLength(255),
                TextInput::make('agnc')
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
                TextColumn::make('date')
                    ->searchable(),
                TextColumn::make('text')
                    ->searchable(),
                TextColumn::make('agnc')
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

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index'  => ListSourceData::route('/'),
            'create' => CreateSourceData::route('/create'),
            'edit'   => EditSourceData::route('/{record}/edit'),
        ];
    }
}
