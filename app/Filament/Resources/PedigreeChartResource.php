<?php

/**
 * Pedigree Chart Resource File
 * 
 * This file is responsible for defining the behavior and presentation of the Pedigree Chart resource in the admin panel.
 * It includes definitions for the form, table, relations, and pages associated with the Pedigree Chart.
 */

namespace App\Filament\Resources;

use App\Models\PedigreeChart;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use App\Filament\Resources\PedigreeChartResource\Pages;

class PedigreeChartResource extends Resource
{
    protected static ?string $model = PedigreeChart::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->maxLength(65535),
            Forms\Components\DatePicker::make('created_at')
                ->hidden(),
            Forms\Components\DatePicker::make('updated_at')
                ->hidden(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('description')
                ->limit(50)
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable(),
        ])->filters([
            //
        ])->actions([
            Tables\Actions\EditAction::make(),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define any relations here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPedigreeCharts::route('/'),
            'create' => Pages\CreatePedigreeChart::route('/create'),
            'edit' => Pages\EditPedigreeChart::route('/{record}/edit'),
        ];
    }
}
