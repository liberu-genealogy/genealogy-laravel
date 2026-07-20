<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Enums\EventType;
use App\Filament\App\Resources\FamilyEventResource\Pages\CreateFamilyEvent;
use App\Filament\App\Resources\FamilyEventResource\Pages\EditFamilyEvent;
use App\Filament\App\Resources\FamilyEventResource\Pages\ListFamilyEvents;
use App\Models\FamilyEvent;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class FamilyEventResource extends AppResource
{
    #[Override]
    protected static ?string $model = FamilyEvent::class;

    #[Override]
    protected static ?string $navigationLabel = 'Family Events';

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '👥 Family Tree';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('family_id')
                    ->required()
                    ->numeric(),
                TextInput::make('places_id')
                    ->numeric(),
                Textarea::make('date')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                // Event type is stored in `title` (see Event::getTitle / EventsService).
                // family_events has no `type` column (commented out in its create migration),
                // so `title` is the only type field. ponytail: constrained input, no enum cast.
                Select::make('title')
                    ->label('Event Type')
                    // Preserve an off-list legacy title on edit (see PersonEventResource).
                    ->options(function ($record): array {
                        $options = EventType::options(EventType::familyCases());
                        if ($record && $record->title && ! array_key_exists($record->title, $options)) {
                            $options[$record->title] = $record->title;
                        }

                        return $options;
                    })
                    ->searchable(),
                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                TextInput::make('converted_date')
                    ->maxLength(255),
                TextInput::make('year')
                    ->numeric(),
                TextInput::make('month')
                    ->numeric(),
                TextInput::make('day')
                    ->numeric(),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('family_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('places_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('converted_date')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('year')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('month')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('day')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListFamilyEvents::route('/'),
            'create' => CreateFamilyEvent::route('/create'),
            'edit' => EditFamilyEvent::route('/{record}/edit'),
        ];
    }
}
