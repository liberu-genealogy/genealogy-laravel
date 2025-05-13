<?php

namespace App\Filament\App\Resources;

use BackedEnum;
use App\Filament\App\Resources\SourceDataResource\Pages;
use App\Models\SourceData;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SourceDataResource extends Resource
{
    protected static ?string $model = SourceData::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

    #[\Override]
    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('group')
                    ->maxLength(255),
                Forms\Components\TextInput::make('gid')
                    ->numeric(),
                Forms\Components\TextInput::make('date')
                    ->maxLength(255),
                Forms\Components\TextInput::make('text')
                    ->maxLength(255),
                Forms\Components\TextInput::make('agnc')
                    ->maxLength(255),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gid')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('text')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agnc')
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
            'index'  => Pages\ListSourceData::route('/'),
            'create' => Pages\CreateSourceData::route('/create'),
            'edit'   => Pages\EditSourceData::route('/{record}/edit'),
        ];
    }
}
