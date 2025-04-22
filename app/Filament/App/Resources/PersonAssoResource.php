<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\PersonAssoResource\Pages;
use App\Models\PersonAsso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PersonAssoResource extends Resource
{
    protected static ?string $model = PersonAsso::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Person Associations';

    protected static ?string $navigationGroup = 'Person';

    #[\Override]
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('group')
                    ->maxLength(255),
                Forms\Components\TextInput::make('gid')
                    ->numeric(),
                Forms\Components\TextInput::make('indi')
                    ->maxLength(255),
                Forms\Components\TextInput::make('rela')
                    ->maxLength(255),
                Forms\Components\TextInput::make('import_confirm')
                    ->required()
                    ->numeric()
                    ->default(0),
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
                Tables\Columns\TextColumn::make('indi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rela')
                    ->searchable(),
                Tables\Columns\TextColumn::make('import_confirm')
                    ->numeric()
                    ->sortable(),
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
            'index'  => Pages\ListPersonAssos::route('/'),
            'create' => Pages\CreatePersonAsso::route('/create'),
            'edit'   => Pages\EditPersonAsso::route('/{record}/edit'),
        ];
    }
}
