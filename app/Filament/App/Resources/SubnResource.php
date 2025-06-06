<?php

namespace App\Filament\App\Resources;

use BackedEnum;
use App\Filament\App\Resources\SubnResource\Pages;
use App\Models\Subn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class SubnResource extends Resource
{
    protected static ?string $model = Subn::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    #[\Override]
    public static function form(Schema $form): Schema
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

    #[\Override]
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
            'index'  => Pages\ListSubns::route('/'),
            'create' => Pages\CreateSubn::route('/create'),
            'edit'   => Pages\EditSubn::route('/{record}/edit'),
        ];
    }
}
