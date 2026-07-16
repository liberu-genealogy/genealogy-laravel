<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\PersonAssoResource\Pages\CreatePersonAsso;
use App\Filament\App\Resources\PersonAssoResource\Pages\EditPersonAsso;
use App\Filament\App\Resources\PersonAssoResource\Pages\ListPersonAssos;
use App\Models\PersonAsso;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class PersonAssoResource extends AppResource
{
    #[Override]
    protected static ?string $model = PersonAsso::class;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    #[Override]
    protected static ?string $navigationLabel = 'Person Associations';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '👥 Family Tree';

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

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListPersonAssos::route('/'),
            'create' => CreatePersonAsso::route('/create'),
            'edit' => EditPersonAsso::route('/{record}/edit'),
        ];
    }
}
