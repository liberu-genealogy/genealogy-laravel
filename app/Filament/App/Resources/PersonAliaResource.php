<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\PersonAliaResource\Pages\CreatePersonAlia;
use App\Filament\App\Resources\PersonAliaResource\Pages\EditPersonAlia;
use App\Filament\App\Resources\PersonAliaResource\Pages\ListPersonAlias;
use App\Models\PersonAlia;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class PersonAliaResource extends AppResource
{
    #[Override]
    protected static ?string $model = PersonAlia::class;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-finger-print';

    #[Override]
    protected static ?string $navigationLabel = 'Person Alia';

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

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListPersonAlias::route('/'),
            'create' => CreatePersonAlia::route('/create'),
            'edit' => EditPersonAlia::route('/{record}/edit'),
        ];
    }
}
