<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SourceDataEvenResource\Pages\CreateSourceDataEven;
use App\Filament\App\Resources\SourceDataEvenResource\Pages\EditSourceDataEven;
use App\Filament\App\Resources\SourceDataEvenResource\Pages\ListSourceDataEvens;
use App\Models\SourceDataEven;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class SourceDataEvenResource extends AppResource
{
    #[Override]
    protected static ?string $model = SourceDataEven::class;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    #[Override]
    protected static ?string $navigationLabel = 'Source Data Events';

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

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListSourceDataEvens::route('/'),
            'create' => CreateSourceDataEven::route('/create'),
            'edit' => EditSourceDataEven::route('/{record}/edit'),
        ];
    }
}
