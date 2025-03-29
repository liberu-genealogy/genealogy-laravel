<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonResource\Pages;
use App\Models\Person;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('givn')->required(),
                Forms\Components\TextInput::make('surn')->required(),
                Forms\Components\Select::make('sex')
                    ->options([
                        'M' => 'Male',
                        'F' => 'Female',
                        'O' => 'Other',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('birthday'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('givn')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('surn')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('sex'),
                Tables\Columns\TextColumn::make('birthday')->date(),
                Tables\Columns\TextColumn::make('age')
                    ->getStateUsing(fn (Person $record): ?int => $record->getAge()),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sex')
                    ->options([
                        'M' => 'Male',
                        'F' => 'Female',
                        'O' => 'Other',
                    ]),
                Tables\Filters\Filter::make('born_before_100_years_ago')
                    ->query(fn ($query) => $query->where('birthday', '<=', now()->subYears(100)->format('Y-m-d'))),
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
            'index' => Pages\ListPeople::route('/'),
            'create' => Pages\CreatePerson::route('/create'),
            'edit' => Pages\EditPerson::route('/{record}/edit'),
        ];
    }
}