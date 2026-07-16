<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TreeResource\Pages\ListTrees;
use App\Models\Tree;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Admin-panel monitor listing family trees across every team (SCOPE §18).
 * Read-only.
 *
 * ponytail: no live "person count" column — a tree has no people FK; a count
 * means recursive TreeBuilderService traversal (Tree::getStats) per row, i.e.
 * N expensive queries per list render. Add a denormalised/cached count column
 * on the model first if the number is actually wanted here.
 */
class TreeResource extends Resource
{
    #[\Override]
    protected static ?string $model = Tree::class;

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = 'Monitoring';

    #[\Override]
    protected static ?string $navigationLabel = 'Trees';

    #[\Override]
    protected static ?int $navigationSort = 2;

    #[\Override]
    protected static ?string $recordTitleAttribute = 'name';

    #[\Override]
    public static function canCreate(): bool
    {
        return false;
    }

    /**
     * Cross-team visibility: Tree uses BelongsToTenant. Strip its team scope so
     * the admin monitor lists every team's trees, not just the admin's own.
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
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('team.name')
                    ->label('Team')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('user.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('rootPerson.name')
                    ->label('Root Person')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('description')
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('team')
                    ->relationship('team', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Team'),
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->defaultSort('created_at', 'desc');
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListTrees::route('/'),
        ];
    }
}
