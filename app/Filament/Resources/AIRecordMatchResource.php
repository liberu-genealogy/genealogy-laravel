<?php

namespace App\Filament\Resources;

use Filament\Actions\Action;
use App\Filament\Resources\AIRecordMatchResource\Pages\ReviewMatches;
use App\Models\AISuggestedMatch;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class AIRecordMatchResource extends Resource
{
    protected static ?string $model = AISuggestedMatch::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-switch-horizontal';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('local_person_id')->label('Local Person ID')->sortable(),
                TextColumn::make('provider')->label('Provider')->sortable(),
                TextColumn::make('external_record_id')->label('External ID'),
                TextColumn::make('confidence')->label('Confidence')->formatStateUsing(fn($state) => round($state * 100, 1) . '%'),
                TextColumn::make('status')->label('Status')->sortable(),
                TextColumn::make('created_at')->label('Created'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('confirm')
                    ->label('Confirm')
                    ->action(fn (AISuggestedMatch $record) => redirect()->route('filament.resources.ai-record-matches.review.confirm', ['record' => $record->id])),
                Action::make('reject')
                    ->label('Reject')
                    ->action(fn (AISuggestedMatch $record) => redirect()->route('filament.resources.ai-record-matches.review.reject', ['record' => $record->id])),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ReviewMatches::route('/'),
        ];
    }
}
