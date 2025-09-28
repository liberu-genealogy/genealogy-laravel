<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\FamilySlgsResource\Pages\ListFamilySlgs;
use App\Filament\App\Resources\FamilySlgsResource\Pages\CreateFamilySlgs;
use App\Filament\App\Resources\FamilySlgsResource\Pages\EditFamilySlgs;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\FamilySlgsResource\Pages;
use App\Models\FamilySlgs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class FamilySlgsResource extends Resource
{
    protected static ?string $model = FamilySlgs::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Family Slugs';

    protected static string | \UnitEnum | null $navigationGroup = '\ud83d\udc65 Family Tree';

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

    public static function getPages(): array
    {
        return [
            'index'  => ListFamilySlgs::route('/'),
            'create' => CreateFamilySlgs::route('/create'),
            'edit'   => EditFamilySlgs::route('/{record}/edit'),
        ];
    }
}
