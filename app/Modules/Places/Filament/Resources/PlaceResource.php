<?php

namespace App\Modules\Places\Filament\Resources;

use App\Models\Place;
use App\Modules\Places\Filament\Resources\PlaceResource\Pages\CreatePlace;
use App\Modules\Places\Filament\Resources\PlaceResource\Pages\EditPlace;
use App\Modules\Places\Filament\Resources\PlaceResource\Pages\ListPlaces;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PlaceResource extends Resource
{
    #[\Override]
    protected static ?string $model = Place::class;

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = 'Geography';

    #[\Override]
    protected static ?string $navigationLabel = 'Places';

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('date')
                    ->maxLength(255),
                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    #[\Override]
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
                    ->getStateUsing(fn ($record): bool => $record->latitude && $record->longitude),
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

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListPlaces::route('/'),
            'create' => CreatePlace::route('/create'),
            'edit' => EditPlace::route('/{record}/edit'),
        ];
    }
}
