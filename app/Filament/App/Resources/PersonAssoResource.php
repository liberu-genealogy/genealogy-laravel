<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Enums\AssociationType;
use App\Filament\App\Resources\PersonAssoResource\Pages\CreatePersonAsso;
use App\Filament\App\Resources\PersonAssoResource\Pages\EditPersonAsso;
use App\Filament\App\Resources\PersonAssoResource\Pages\ListPersonAssos;
use App\Models\Person;
use App\Models\PersonAsso;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class PersonAssoResource extends AppResource
{
    #[Override]
    protected static ?string $model = PersonAsso::class;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    #[Override]
    protected static ?string $navigationLabel = 'Person Associations';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '👥 Family Tree';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('gid')
                    ->label('Person')
                    ->required()
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search): array => self::searchPeople($search))
                    ->getOptionLabelUsing(fn ($value): ?string => Person::find($value)?->fullname()),
                Select::make('indi')
                    ->label('Associated person')
                    ->required()
                    ->searchable()
                    // `indi` is a varchar holding a person id — the importer parks a raw
                    // xref ("@I5@") there before resolving it. Keep UI writes to match.
                    ->dehydrateStateUsing(fn ($state): string => (string) $state)
                    ->getSearchResultsUsing(fn (string $search): array => self::searchPeople($search))
                    ->getOptionLabelUsing(fn ($value): ?string => Person::find($value)?->fullname()),
                Select::make('rela')
                    ->label('Relationship')
                    ->required()
                    // RELA is free text in GEDCOM; offer an imported value back rather
                    // than let required() silently rewrite it.
                    ->options(fn (?PersonAsso $record): array => $record?->rela !== null && $record->type() === null
                        ? [$record->rela => $record->rela] + AssociationType::options()
                        : AssociationType::options()),
                // Not user-facing: this resource only manages resolved person-level
                // associations. import_confirm = 0 is the importer's "xref not yet
                // resolved" marker, so anything created here is already resolved.
                Hidden::make('group')
                    ->default(PersonAsso::GROUP_INDI),
                Hidden::make('import_confirm')
                    ->default(1),
            ]);
    }

    /** @return array<int, string> */
    private static function searchPeople(string $search): array
    {
        return Person::query()
            ->where(fn ($query) => $query
                ->where('givn', 'like', "%{$search}%")
                ->orWhere('surn', 'like', "%{$search}%"))
            ->limit(50)
            ->get()
            ->mapWithKeys(fn (Person $person): array => [$person->getKey() => $person->fullname()])
            ->all();
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['person', 'associate']))
            ->columns([
                TextColumn::make('gid')
                    ->label('Person')
                    ->getStateUsing(fn (PersonAsso $record): string => $record->person?->fullname()
                        ?? (string) ($record->gid ?? 'Unknown'))
                    ->searchable(),
                TextColumn::make('indi')
                    ->label('Associated person')
                    // Rows still holding an unresolved xref have no Person to resolve to.
                    ->getStateUsing(fn (PersonAsso $record): string => $record->associate?->fullname()
                        ?? $record->indi
                        ?? 'Unresolved')
                    ->searchable(),
                TextColumn::make('rela')
                    ->label('Relationship')
                    ->getStateUsing(fn (PersonAsso $record): string => $record->typeLabel())
                    ->badge()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ListPersonAssos::route('/'),
            'create' => CreatePersonAsso::route('/create'),
            'edit' => EditPersonAsso::route('/{record}/edit'),
        ];
    }
}
