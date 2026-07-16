<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceResource\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Evidence link for a Source: the citations (SCOPE §9) that reference it,
 * with the GEDCOM confidence/page/volume attributes exposed. This is the
 * closest link the schema supports — `citations.source_id` (Source hasMany
 * Citation). There is no person_id/group-gid on the citations table, so
 * person↔citation cannot be expressed here; see EvidenceLinkingTest / report.
 */
class CitationsRelationManager extends RelationManager
{
    #[\Override]
    protected static string $relationship = 'citations';

    #[\Override]
    protected static ?string $title = 'Evidence (Citations)';

    #[\Override]
    protected static ?string $recordTitleAttribute = 'name';

    #[\Override]
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            Textarea::make('description')
                ->columnSpanFull(),
            TextInput::make('confidence')
                ->numeric()
                ->minValue(0)
                ->maxValue(3)
                ->helperText('GEDCOM QUAY 0-3: certainty this evidence supports the fact.'),
            TextInput::make('page')
                ->numeric(),
            TextInput::make('volume')
                ->numeric(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('confidence')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('page')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('volume')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
