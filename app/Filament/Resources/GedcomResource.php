<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GedcomResource\Pages;
use App\Models\Gedcom;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GedcomResource extends Resource
use Filament\Forms\Components\FileUpload;
use App\Jobs\ImportGedcom;
use Illuminate\Support\Facades\Storage;
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
            ->afterStateUpdated(function ($state, $set, $livewire) {
                if ($state === null) {
                    return;
                }
                $path = $state->store('gedcom-form-imports', 'private');
                ImportGedcom::dispatch($livewire->user(), Storage::path($path));
            }),
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
            'index'  => Pages\ListGedcoms::route('/'),
            'create' => Pages\CreateGedcom::route('/create'),
            'view'   => Pages\ViewGedcom::route('/{record}'),
            'edit'   => Pages\EditGedcom::route('/{record}/edit'),
        ];
    }

    private static function import(): array
    {
    }
}
