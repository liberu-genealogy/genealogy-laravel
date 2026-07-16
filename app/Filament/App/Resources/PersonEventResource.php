<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use Override;
use App\Enums\EventType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\PersonEventResource\Pages\ListPersonEvents;
use App\Filament\App\Resources\PersonEventResource\Pages\CreatePersonEvent;
use App\Filament\App\Resources\PersonEventResource\Pages\EditPersonEvent;
use BackedEnum;
use App\Filament\App\Resources\PersonEventResource\Pages;
use App\Models\PersonEvent;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\App\Resources\AppResource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class PersonEventResource extends AppResource
{
    #[\Override]
    protected static ?string $model = PersonEvent::class;

    #[\Override]
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar';
    #[\Override]
    protected static ?string $navigationLabel = 'Person Events';
    #[\Override]
    protected static string | \UnitEnum | null $navigationGroup = '👥 Family Tree';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('converted_date')
                    ->maxLength(255),
                TextInput::make('year')
                    ->numeric(),
                TextInput::make('month')
                    ->numeric(),
                TextInput::make('day')
                    ->numeric(),
                TextInput::make('type')
                    ->maxLength(255),
                Textarea::make('attr')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                TextInput::make('plac')
                    ->maxLength(255),
                TextInput::make('addr_id')
                    ->numeric(),
                TextInput::make('phon')
                    ->maxLength(255),
                Textarea::make('caus')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                TextInput::make('age')
                    ->maxLength(255),
                TextInput::make('agnc')
                    ->maxLength(255),
                TextInput::make('adop')
                    ->maxLength(255),
                TextInput::make('adop_famc')
                    ->maxLength(255),
                TextInput::make('birt_famc')
                    ->maxLength(255),
                TextInput::make('person_id')
                    ->numeric(),
                // Event type is stored in `title` (see Event::getTitle / EventsService).
                // ponytail: constrained Select, not a strict cast — legacy imports hold
                // GEDCOM tags beyond these 10, and a backed-enum cast would ValueError on read.
                Select::make('title')
                    ->label('Event Type')
                    // Merge the record's own value in when it isn't one of the 10 enum
                    // types (legacy GEDCOM imports), so editing an existing event can't
                    // silently blank a valid-but-off-list title on save.
                    ->options(function ($record): array {
                        $options = EventType::options(EventType::personCases());
                        if ($record && $record->title && ! array_key_exists($record->title, $options)) {
                            $options[$record->title] = $record->title;
                        }
                        return $options;
                    })
                    ->searchable(),
                TextInput::make('date')
                    ->maxLength(255),
                TextInput::make('description')
                    ->maxLength(255),
                TextInput::make('places_id')
                    ->numeric(),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('plac')
                    ->searchable(),
                TextColumn::make('addr_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('phon')
                    ->searchable(),
                TextColumn::make('age')
                    ->searchable(),
                TextColumn::make('agnc')
                    ->searchable(),
                TextColumn::make('adop')
                    ->searchable(),
                TextColumn::make('adop_famc')
                    ->searchable(),
                TextColumn::make('birt_famc')
                    ->searchable(),
                TextColumn::make('person_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('date')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('places_id')
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

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index'  => ListPersonEvents::route('/'),
            'create' => CreatePersonEvent::route('/create'),
            'edit'   => EditPersonEvent::route('/{record}/edit'),
        ];
    }
}
