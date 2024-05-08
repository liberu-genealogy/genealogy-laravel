<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewFamilySlgsResource\Pages;
use App\Filament\Resources\NewFamilySlgsResource\RelationManagers;
use App\Models\NewFamilySlgs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NewFamilySlgsResource extends Resource
{
    protected static ?string $model = NewFamilySlgs::class;

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
            'index' => Pages\ListNewFamilySlgs::route('/'),
            'create' => Pages\CreateNewFamilySlgs::route('/create'),
            'edit' => Pages\EditNewFamilySlgs::route('/{record}/edit'),
        ];
    }
}
