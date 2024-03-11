<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedigreeChartResource\Pages;
use App\Models\Person;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

/**
 * Class PedigreeChartResource
 *
 * This class represents the resource for managing pedigree charts.
 */
class PedigreeChartResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('gid')->required(),
                Forms\Components\TextInput::make('givn')->required(),
                Forms\Components\TextInput::make('surn')->required(),
                Forms\Components\Select::make('sex')
                    ->options([
                        'M' => 'Male',
                        'F' => 'Female',
                    ])->required(),
                Forms\Components\DatePicker::make('birthday'),
                Forms\Components\DatePicker::make('deathday'),
                Forms\Components\Textarea::make('description'),
                Forms\Components\TextInput::make('email'),
                Forms\Components\TextInput::make('phone'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gid'),
                Tables\Columns\TextColumn::make('givn'),
                Tables\Columns\TextColumn::make('surn'),
                Tables\Columns\TextColumn::make('sex')->enum([
                    'M' => 'Male',
                    'F' => 'Female',
                ]),
                Tables\Columns\TextColumn::make('birthday')->date(),
                Tables\Columns\TextColumn::make('deathday')->date(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone'),
            ])
            ->filters([
                //
            ]);
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
