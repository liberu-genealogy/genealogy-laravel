<?php

namespace App\Modules\Places\Filament\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Modules\Places\Filament\Resources\PlaceResource\Pages\ListPlaces;
use App\Modules\Places\Filament\Resources\PlaceResource\Pages\CreatePlace;
use App\Modules\Places\Filament\Resources\PlaceResource\Pages\EditPlace;
use App\Models\Place;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PlaceResource extends Resource
{
    protected static ?string $model = Place::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-map-pin';

    protected static string | \UnitEnum | null $navigationGroup = 'Geography';

    protected static ?string $navigationLabel = 'Places';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('city')
                    ->maxLength(255),
                TextInput::make('state')
                    ->maxLength(255),
                TextInput::make('country')
                    ->maxLength(255),
                TextInput::make('postal_code')
                    ->maxLength(20),
                Grid::make(2)
                    ->schema([
                        TextInput::make('latitude')
                            ->numeric()
                            ->step(0.000001)
                            ->minValue(-90)
                            ->maxValue(90),
                        TextInput::make('longitude')
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
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('state')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('country')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('has_coordinates')
                    ->label('Coordinates')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->latitude && $record->longitude),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('country')
                    ->options(fn () => Place::distinct('country')->whereNotNull('country')->pluck('country', 'country')),
                TernaryFilter::make('has_coordinates')
                    ->label('Has Coordinates')
                    ->placeholder('All places')
                    ->trueLabel('With coordinates')
                    ->falseLabel('Without coordinates')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('latitude')->whereNotNull('longitude'),
                        false: fn (Builder $query) => $query->whereNull('latitude')->orWhereNull('longitude'),
                    ),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlaces::route('/'),
            'create' => CreatePlace::route('/create'),
            'edit' => EditPlace::route('/{record}/edit'),
        ];
    }
}
