<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GedcomResource\Pages;
use App\Filament\Resources\GedcomResource\RelationManagers;
use App\Models\Gedcom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GedcomResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = Gedcom::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('attachment')
                    ->required()
                    ->maxSize(100000)
    		    ->directory('gedcom-form-imports')
    		    ->visibility('private')
		    ->afterStateUpdated(
	ImportGedcom::dispatch($request->user(), $manager->storagePath($path), $state))
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListGedcoms::route('/'),
            'create' => Pages\CreateGedcom::route('/create'),
            'view' => Pages\ViewGedcom::route('/{record}'),
            'edit' => Pages\EditGedcom::route('/{record}/edit'),
        ];
    }

    private static function import(): array
    {
    }

}
