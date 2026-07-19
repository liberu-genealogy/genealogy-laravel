<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonResource\RelationManagers;

use App\Filament\App\Resources\AppRelationManager;
use App\Filament\App\Resources\SourceRefResource;
use App\Models\SourceRef;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * The sources evidencing this person as a whole (GEDCOM SOUR, group 'indi').
 *
 * Rows created here are stamped group='indi' by Person::sourceRefs()'s
 * withAttributes(). The GEDCOM importer never writes that group — it only emits
 * the finer-grained indi_name/indi_even/indi_asso/indi_lds — so this is the only
 * thing that populates the person half of CompletenessService::sourceCompleteness().
 */
class SourcesRelationManager extends AppRelationManager
{
    #[\Override]
    protected static string $relationship = 'sourceRefs';

    #[\Override]
    protected static ?string $title = 'Sources';

    #[\Override]
    public function form(Schema $schema): Schema
    {
        // group/gid come from the relation, not the form.
        return $schema->components(SourceRefResource::citationComponents());
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('source.name')
                    ->label('Source')
                    // Importer-created sources fill `titl`, not `name`.
                    ->getStateUsing(fn (SourceRef $record): string => $record->source?->name
                        ?: $record->source?->titl
                        ?: '—'),
                TextColumn::make('page')
                    ->label('Page / citation detail'),
                TextColumn::make('quay')
                    ->label('Confidence')
                    ->getStateUsing(fn (SourceRef $record): string => $record->qualityLabel()),
                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
