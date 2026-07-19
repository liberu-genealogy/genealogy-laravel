<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\DatabaseResource\Pages\CreateDatabase;
use App\Filament\App\Resources\DatabaseResource\Pages\EditDatabase;
use App\Filament\App\Resources\DatabaseResource\Pages\ListDatabases;
use App\Models\Tree;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class DatabaseResource extends AppResource
{
    #[Override]
    protected static ?string $model = Tree::class;

    #[Override]
    protected static bool $isScopedToTenant = false;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-circle-stack';

    #[Override]
    protected static ?string $navigationLabel = 'Databases';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '🛠️ Data & Import';

    #[Override]
    protected static ?int $navigationSort = 1;

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('description')
                    ->maxLength(255),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
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

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListDatabases::route('/'),
            'create' => CreateDatabase::route('/create'),
            'edit' => EditDatabase::route('/{record}/edit'),
        ];
    }
}
