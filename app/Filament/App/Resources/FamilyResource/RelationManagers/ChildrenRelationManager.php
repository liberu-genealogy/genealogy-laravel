<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\FamilyResource\RelationManagers;

use App\Enums\PedigreeType;
use App\Filament\App\Resources\AppRelationManager;
use App\Models\Person;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * The children of a family. Children link upward via people.child_in_family_id
 * (Family::children() is a plain hasMany), so associate/dissociate just set/null
 * that FK — no pivot. Each child's link type (biological / adopted / foster /
 * sealing) is its people.pedigree (GEDCOM FAMC.PEDI); null reads as biological.
 *
 * AssociateAction attaches an existing person with the default (biological)
 * pedigree; set a non-default pedigree via the row Edit or when creating a new
 * child. A person has one child_in_family_id, so associating a child here moves
 * them out of any prior family — expected for this GEDCOM-shaped schema.
 */
class ChildrenRelationManager extends AppRelationManager
{
    #[\Override]
    protected static string $relationship = 'children';

    // Person's belongsTo back to the family is childInFamily(), not the family() that
    // Filament would guess — associate/dissociate need it to set/null child_in_family_id.
    #[\Override]
    protected static ?string $inverseRelationship = 'childInFamily';

    #[\Override]
    protected static ?string $title = 'Children';

    #[\Override]
    protected static ?string $recordTitleAttribute = 'givn';

    #[\Override]
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('givn')
                ->label('Given name')
                ->maxLength(255),
            TextInput::make('surn')
                ->label('Surname')
                ->maxLength(255),
            Select::make('pedigree')
                ->label('Relationship to this family')
                ->options(PedigreeType::options())
                ->placeholder('Biological'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->state(fn (Person $record): string => $record->fullname())
                    ->searchable(['givn', 'surn']),
                TextColumn::make('pedigree')
                    ->label('Relationship')
                    ->state(fn (Person $record): string => $record->pedigreeLabel())
                    ->badge(),
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make()
                    ->recordSelect(fn (Select $select): Select => $select
                        ->label('Child')
                        ->getSearchResultsUsing(fn (string $search): array => Person::query()
                            ->where(fn ($query) => $query
                                ->where('givn', 'like', "%{$search}%")
                                ->orWhere('surn', 'like', "%{$search}%"))
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn (Person $person): array => [$person->getKey() => $person->fullname()])
                            ->all())
                        ->getOptionLabelUsing(fn ($value): ?string => Person::find($value)?->fullname())),
            ])
            ->actions([
                EditAction::make(),
                DissociateAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                ]),
            ]);
    }
}
