<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\AIRecordMatchResource\Pages\ReviewMatches;
use App\Models\AISuggestedMatch;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AIRecordMatchResource extends AppResource
{
    #[\Override]
    protected static ?string $model = AISuggestedMatch::class;

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    #[\Override]
    protected static ?string $navigationLabel = 'AI Record Matches';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '🔍 Research & Analysis';

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('local_person_id')->label('Local Person ID')->sortable(),
                TextColumn::make('provider')->label('Provider')->sortable(),
                TextColumn::make('external_record_id')->label('External ID'),
                TextColumn::make('confidence')->label('Confidence')->formatStateUsing(fn ($state): string => round($state * 100, 1).'%'),
                TextColumn::make('status')->label('Status')->sortable(),
                TextColumn::make('created_at')->label('Created'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('confirm')
                    ->label('Confirm')
                    ->color('success')
                    ->action(function (AISuggestedMatch $record): void {
                        $record->update(['status' => 'confirmed']);
                        Notification::make()->title('Match Confirmed')->success()->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->action(function (AISuggestedMatch $record): void {
                        $record->update(['status' => 'rejected']);
                        Notification::make()->title('Match Rejected')->success()->send();
                    }),
            ]);
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ReviewMatches::route('/'),
        ];
    }
}
