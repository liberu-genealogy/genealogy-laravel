<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\FamilySlgsResource\Pages\CreateFamilySlgs;
use App\Filament\App\Resources\FamilySlgsResource\Pages\EditFamilySlgs;
use App\Filament\App\Resources\FamilySlgsResource\Pages\ListFamilySlgs;
use App\Models\FamilySlgs;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class FamilySlgsResource extends AppResource
{
    #[Override]
    protected static ?string $model = FamilySlgs::class;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-link';

    #[Override]
    protected static ?string $navigationLabel = 'Family Slugs';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '👥 Family Tree';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('family_id')
                    ->numeric(),
                TextInput::make('stat')
                    ->maxLength(255),
                TextInput::make('date')
                    ->maxLength(255),
                TextInput::make('plac')
                    ->maxLength(255),
                TextInput::make('temp')
                    ->maxLength(255),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('family_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stat')
                    ->searchable(),
                TextColumn::make('date')
                    ->searchable(),
                TextColumn::make('plac')
                    ->searchable(),
                TextColumn::make('temp')
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
            'index' => ListFamilySlgs::route('/'),
            'create' => CreateFamilySlgs::route('/create'),
            'edit' => EditFamilySlgs::route('/{record}/edit'),
        ];
    }
}
