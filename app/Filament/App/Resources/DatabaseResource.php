<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\DatabaseResource\Pages;
use App\Models\Database;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DatabaseResource extends Resource
{
    protected static ?string $model = Database::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index'  => Pages\ListDatabases::route('/'),
            'create' => Pages\CreateDatabase::route('/create'),
            'edit'   => Pages\EditDatabase::route('/{record}/edit'),
        ];
    }
}
