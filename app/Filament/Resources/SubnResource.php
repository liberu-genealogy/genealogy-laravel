<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubnResource\Pages;
use App\Models\Subn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubnResource extends Resource
{
    protected static ?string $model = Subn::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('subm')
                    ->maxLength(255),
                Forms\Components\TextInput::make('famf')
                    ->maxLength(255),
                Forms\Components\TextInput::make('temp')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ance')
                    ->maxLength(255),
                Forms\Components\TextInput::make('desc')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ordi')
                    ->maxLength(255),
                Forms\Components\TextInput::make('rin')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subm')
                    ->searchable(),
                Tables\Columns\TextColumn::make('famf')
                    ->searchable(),
                Tables\Columns\TextColumn::make('temp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ance')
                    ->searchable(),
                Tables\Columns\TextColumn::make('desc')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ordi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rin')
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
            'index'  => Pages\ListSubns::route('/'),
            'create' => Pages\CreateSubn::route('/create'),
            'edit'   => Pages\EditSubn::route('/{record}/edit'),
        ];
    }
}
