<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\FamilySlgsResource\Pages;
use App\Models\FamilySlgs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FamilySlgsResource extends Resource
{
    protected static ?string $model = FamilySlgs::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Family Slugs';

    protected static ?string $navigationGroup = 'Family';

    public static function form(Form $form): Form
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

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
