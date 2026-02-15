<?php

namespace App\Filament\App\Resources\AIRecordMatchResource\Pages;

use Filament\Actions\Action;
use App\Filament\App\Resources\AIRecordMatchResource;
use App\Models\AISuggestedMatch;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Http;

class ReviewMatches extends ListRecords
{
    protected static string $resource = AIRecordMatchResource::class;

    protected function getTableActions(): array
    {
        return [
            Action::make('confirm')
                ->label('Confirm')
                ->action(function (AISuggestedMatch $record) {
                    // call controller endpoint
                    Http::post(route('ai.matches.confirm', ['suggestion' => $record->id]));
                    $this->notify('success', 'Confirmed');
                }),
            Action::make('reject')
                ->label('Reject')
                ->action(function (AISuggestedMatch $record) {
                    Http::post(route('ai.matches.reject', ['suggestion' => $record->id]));
                    $this->notify('success', 'Rejected');
                }),
        ];
    }
}
