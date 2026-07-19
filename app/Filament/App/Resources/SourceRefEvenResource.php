<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SourceRefEvenResource\Pages\CreateSourceRefEven;
use App\Filament\App\Resources\SourceRefEvenResource\Pages\EditSourceRefEven;
use App\Filament\App\Resources\SourceRefEvenResource\Pages\ListSourceRefEvens;
use App\Models\SourceRefEven;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class SourceRefEvenResource extends AppResource
{
    #[Override]
    protected static ?string $model = SourceRefEven::class;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-link';

    #[Override]
    protected static ?string $navigationLabel = 'Source Reference Events';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '📚 Sources & Citations';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->maxLength(255),
                TextInput::make('gid')
                    ->numeric(),
                TextInput::make('even')
                    ->maxLength(255),
                TextInput::make('role')
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
                TextColumn::make('even')
                    ->searchable(),
                TextColumn::make('role')
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
            'index' => ListSourceRefEvens::route('/'),
            'create' => CreateSourceRefEven::route('/create'),
            'edit' => EditSourceRefEven::route('/{record}/edit'),
        ];
    }
}
