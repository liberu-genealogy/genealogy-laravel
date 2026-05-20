<?php

namespace App\Modules\Person\Filament\Resources;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use App\Modules\Person\Filament\Resources\DuplicateMatchResource\Pages\ListDuplicateMatches;
use App\Models\DuplicateMatch;
use App\Services\PersonMergeService;
use App\Models\Person;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class DuplicateMatchResource extends Resource
{
    protected static ?string $model = DuplicateMatch::class;

    protected static ?string $navigationLabel = 'Duplicate Suggestions';
    protected static string | \UnitEnum | null $navigationGroup = 'Genealogy';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-identification';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('primaryPerson.name')
                    ->label('Primary')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('duplicatePerson.name')
                    ->label('Candidate')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('confidence_score')
                    ->label('Confidence')
                    ->formatStateUsing(fn($state) => sprintf('%.1f%%', $state * 100))
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->sortable(),
                TextColumn::make('match_data')
                    ->label('Match data')
                    ->toggleable()
                    ->wrap()
                    ->formatStateUsing(fn($state) => json_encode($state)),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Detected'),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'reviewed' => 'Reviewed',
                    'accepted' => 'Accepted',
                    'rejected' => 'Rejected',
                    'merged' => 'Merged',
                ]),
            ])
            ->actions([
                Action::make('review')
                    ->label('Review')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Review duplicate suggestion')
                    ->modalSubheading(fn (DuplicateMatch $record) => 'Confidence: ' . sprintf('%.1f%%', $record->confidence_score * 100))
                    ->modalContent(function (DuplicateMatch $record): string {
                        $p = $record->primaryPerson;
                        $d = $record->duplicatePerson;
                        $data = $record->match_data ?? [];
                        $md = htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                        return "<div>
                            <p><strong>Primary</strong>: {$p->name} (ID: {$p->id})</p>
                            <p><strong>Candidate</strong>: {$d->name} (ID: {$d->id})</p>
                            <pre style=\"max-height:250px;overflow:auto;background:#f6f8fa;padding:8px;border-radius:4px;\">{$md}</pre>
                        </div>";
                    })
                    ->modalActions([
                        Action::make('merge')
                            ->label('Merge (candidate → primary)')
                            ->color('success')
                            ->requiresConfirmation()
                            ->action(function (DuplicateMatch $record, array $data) {
                                $mergeService = app(PersonMergeService::class);
                                $primary = $record->primaryPerson;
                                $candidate = $record->duplicatePerson;
                                if (!$primary || !$candidate) {
                                    $record->status = 'rejected';
                                    $record->save();
                                    return $record->fresh();
                                }
                                $mergeService->merge($primary, $candidate);
                                $record->status = 'merged';
                                $record->save();
                                return $record->fresh();
                            }),
                        Action::make('reject')
                            ->label('Reject')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->action(function (DuplicateMatch $record) {
                                $record->status = 'rejected';
                                $record->reviewed_at = now();
                                $record->save();
                            }),
                    ]),
                Action::make('open')
                    ->label('Open persons')
                    ->url(fn (DuplicateMatch $record) => route('filament.resources.persons.edit', ['record' => $record->primary_person_id]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                BulkAction::make('merge_selected')
                    ->label('Merge selected (pairwise)')
                    ->action(function (Collection $records) {
                        $service = app(PersonMergeService::class);
                        foreach ($records as $record) {
                            if ($record->status === 'merged') {
                                continue;
                            }
                            $primary = $record->primaryPerson;
                            $candidate = $record->duplicatePerson;
                            if ($primary && $candidate) {
                                $service->merge($primary, $candidate);
                                $record->status = 'merged';
                                $record->save();
                            }
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDuplicateMatches::route('/'),
        ];
    }
}
