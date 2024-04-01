<?php

namespace App\Filament\Resources\NewDnaMatchingResource\Pages;

use App\Filament\Resources\NewDnaMatchingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewDnaMatching extends EditRecord
{
    protected static string $resource = NewDnaMatchingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
