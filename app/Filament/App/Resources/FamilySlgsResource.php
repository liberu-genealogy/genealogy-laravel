<?php

namespace App\Filament\App\Resources;

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

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Family Slugs';

    protected static string | UnitEnum | null $navigationGroup = 'Family';

    #[\Override]
    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('family_id')
                    ->numeric(),
                Forms\Components\TextInput::make('stat')
                    ->maxLength(255),
                Forms\Components\TextInput::make('date')
                    ->maxLength(255),
                Forms\Components\TextInput::make('plac')
                    ->maxLength(255),
                Forms\Components\TextInput::make('temp')
                    ->maxLength(255),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('family_id')
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('stat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plac')
                    ->searchable(),
                Tables\Columns\TextColumn::make('temp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFamilySlgs::route('/'),
            'create' => Pages\CreateFamilySlgs::route('/create'),
            'edit'   => Pages\EditFamilySlgs::route('/{record}/edit'),
        ];
    }
}
