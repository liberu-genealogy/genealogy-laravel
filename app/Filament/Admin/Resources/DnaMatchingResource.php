<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DnaMatchingResource\Pages\ListDnaMatchings;
use App\Models\DnaMatching;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Admin-panel monitor for DNA matches across every team (SCOPE §18).
 * Read-only.
 */
class DnaMatchingResource extends Resource
{
    #[\Override]
    protected static ?string $model = DnaMatching::class;

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-puzzle-piece';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = 'Monitoring';

    #[\Override]
    protected static ?string $navigationLabel = 'DNA Matches';

    #[\Override]
    protected static ?int $navigationSort = 3;

    #[\Override]
    public static function canCreate(): bool
    {
        return false;
    }

    /**
     * Cross-team visibility: DnaMatching uses BelongsToTenant. Strip its team
     * scope so the admin monitor spans all teams, not just the admin's own.
     */
    #[\Override]
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('match_name')
                    ->label('Match Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('team.name')
                    ->label('Team')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('predicted_relationship')
                    ->label('Relationship')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->color(fn (?string $state): string => match ($state) {
                        'Parent/Child', 'Full Sibling' => 'success',
                        'Grandparent/Grandchild' => 'warning',
                        'First Cousin' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('total_shared_cm')
                    ->label('Shared cM')
                    ->numeric()
                    ->sortable()
                    ->suffix(' cM'),
                TextColumn::make('confidence_level')
                    ->label('Confidence')
                    ->numeric()
                    ->sortable()
                    ->suffix('%')
                    ->color(fn (?float $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('largest_cm_segment')
                    ->label('Largest Segment')
                    ->numeric()
                    ->sortable()
                    ->suffix(' cM')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('analysis_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('predicted_relationship')
                    ->options([
                        'Parent/Child' => 'Parent/Child',
                        'Full Sibling' => 'Full Sibling',
                        'Grandparent/Grandchild' => 'Grandparent/Grandchild',
                        'Aunt/Uncle or Half Sibling' => 'Aunt/Uncle or Half Sibling',
                        'First Cousin' => 'First Cousin',
                        'Second Cousin' => 'Second Cousin',
                        'Distant Cousin' => 'Distant Cousin',
                    ])
                    ->label('Relationship'),
                Filter::make('high_confidence')
                    ->query(fn (Builder $query): Builder => $query->where('confidence_level', '>=', 80))
                    ->label('High Confidence (≥80%)'),
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->defaultSort('total_shared_cm', 'desc');
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListDnaMatchings::route('/'),
        ];
    }
}
