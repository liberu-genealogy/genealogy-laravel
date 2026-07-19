<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonResource\RelationManagers;

use App\Enums\AssociationType;
use App\Filament\App\Resources\AppRelationManager;
use App\Models\Person;
use App\Models\PersonAsso;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * GEDCOM ASSO — the step-parents, guardians, godparents and witnesses that no
 * family record expresses. `group` and `gid` are stamped by the `associations`
 * relation itself (see Person::associations), so neither appears in the form.
 */
class AssociationsRelationManager extends AppRelationManager
{
    #[\Override]
    protected static string $relationship = 'associations';

    #[\Override]
    protected static ?string $title = 'Associations';

    #[\Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('indi')
                    ->label('Associated person')
                    ->required()
                    ->searchable()
                    // `indi` is a varchar: the importer parks a raw xref ("@I5@") there
                    // before resolving it. Keep UI writes stringly-typed to match.
                    ->dehydrateStateUsing(fn ($state): string => (string) $state)
                    ->getSearchResultsUsing(fn (string $search): array => Person::query()
                        ->whereKeyNot($this->ownerRecord->getKey())
                        ->where(fn ($query) => $query
                            ->where('givn', 'like', "%{$search}%")
                            ->orWhere('surn', 'like', "%{$search}%"))
                        ->limit(50)
                        ->get()
                        ->mapWithKeys(fn (Person $person): array => [$person->getKey() => $person->fullname()])
                        ->all())
                    ->getOptionLabelUsing(fn ($value): ?string => Person::find($value)?->fullname()),
                Select::make('rela')
                    ->label('Relationship')
                    ->required()
                    // RELA is free text in GEDCOM, so an imported row may hold a value
                    // outside our curated set. Offer it back rather than let required()
                    // silently rewrite it to a case the user never chose.
                    ->options(fn (?PersonAsso $record): array => $record?->rela !== null && $record->type() === null
                        ? [$record->rela => $record->rela] + AssociationType::options()
                        : AssociationType::options()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('associate'))
            ->columns([
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
                    ->label('Added')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // The UI only creates resolved rows; import_confirm = 0 is the
                        // importer's marker for an xref still awaiting resolution.
                        $data['import_confirm'] = 1;

                        return $data;
                    }),
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
}
