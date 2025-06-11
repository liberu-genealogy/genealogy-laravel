<?php

namespace App\Modules\Places\Filament\Resources;

use App\Models\Place;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PlaceResource extends Resource
{
    protected static ?string $model = Place::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Geography';

    protected static ?string $navigationLabel = 'Places';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->maxLength(255),
                Forms\Components\TextInput::make('state')
                    ->maxLength(255),
                Forms\Components\TextInput::make('country')
                    ->maxLength(255),
                Forms\Components\TextInput::make('postal_code')
                    ->maxLength(20),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->numeric()
                            ->step(0.000001)
                            ->minValue(-90)
                            ->maxValue(90),
                        Forms\Components\TextInput::make('longitude')
                            ->numeric()
                            ->step(0.000001)
                            ->minValue(-180)
                            ->maxValue(180),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('has_coordinates')
                    ->label('Coordinates')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->latitude && $record->longitude),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('country')
                    ->options(fn () => Place::distinct('country')->whereNotNull('country')->pluck('country', 'country')),
                Tables\Filters\TernaryFilter::make('has_coordinates')
                    ->label('Has Coordinates')
                    ->placeholder('All places')
                    ->trueLabel('With coordinates')
                    ->falseLabel('Without coordinates')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('latitude')->whereNotNull('longitude'),
                        false: fn (Builder $query) => $query->whereNull('latitude')->orWhereNull('longitude'),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Modules\Places\Filament\Resources\PlaceResource\Pages\ListPlaces::route('/'),
            'create' => \App\Modules\Places\Filament\Resources\PlaceResource\Pages\CreatePlace::route('/create'),
            'edit' => \App\Modules\Places\Filament\Resources\PlaceResource\Pages\EditPlace::route('/{record}/edit'),
        ];
    }
}