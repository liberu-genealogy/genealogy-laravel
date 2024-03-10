<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DnaResource\Pages;
use App\Jobs\ImportGedcom;
use App\Models\Dna;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class DnaResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = Dna::class;

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
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('variable_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_name')
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
            'index'  => Pages\ListDnas::route('/'),
            'create' => Pages\CreateDna::route('/create'),
            'edit'   => Pages\EditDna::route('/{record}/edit'),
        ];
    }
}
