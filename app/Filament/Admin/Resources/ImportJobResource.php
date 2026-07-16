<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ImportJobResource\Pages\ListImportJobs;
use App\Filament\Admin\Resources\ImportJobResource\Pages\ViewImportJob;
use App\Models\ImportJob;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Admin-panel monitor for import/export jobs across every team (SCOPE §18).
 * Read-only: view the run, its status and progress — no create/edit/delete.
 */
class ImportJobResource extends Resource
{
    #[\Override]
    protected static ?string $model = ImportJob::class;

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = 'Monitoring';

    #[\Override]
    protected static ?string $navigationLabel = 'Import Jobs';

    #[\Override]
    protected static ?int $navigationSort = 1;

    #[\Override]
    public static function canCreate(): bool
    {
        return false;
    }

    /**
     * Cross-team visibility: ImportJob uses BelongsToTenant, whose global scope
     * pins every read to the admin's own current team. Strip it here so the
     * monitor actually spans all teams (the whole point of an admin view).
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
                TextColumn::make('slug')
                    ->label('Import ID')
                    ->searchable()
                    ->copyable()
                    ->limit(16),
                TextColumn::make('team.name')
                    ->label('Team')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('user_id')
                    ->label('User ID')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'complete'   => 'success',
                        'failed'     => 'danger',
                        'processing' => 'info',
                        'queue'      => 'warning',
                        default      => 'gray',
                    }),
                TextColumn::make('progress')
                    ->formatStateUsing(fn (int $state): string => $state . '%')
                    ->color(fn (int $state): string => match (true) {
                        $state === 100 => 'success',
                        $state >= 50   => 'info',
                        $state > 0     => 'warning',
                        default        => 'gray',
                    }),
                TextColumn::make('people_imported')
                    ->label('People')
                    ->numeric()
                    ->toggleable(),
                TextColumn::make('families_imported')
                    ->label('Families')
                    ->numeric()
                    ->toggleable(),
                TextColumn::make('error_message')
                    ->label('Error')
                    ->limit(60)
                    ->tooltip(fn (?string $state): string => $state ?? '')
                    ->color('danger')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Queued At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'queue'      => 'Queued',
                        'processing' => 'Processing',
                        'complete'   => 'Complete',
                        'failed'     => 'Failed',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([])
            ->defaultSort('created_at', 'desc');
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListImportJobs::route('/'),
            'view'  => ViewImportJob::route('/{record}'),
        ];
    }
}
