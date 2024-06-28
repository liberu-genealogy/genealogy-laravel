<?php

namespace App\Filament\Resources\DnaMatchingResource\Pages;

use App\Filament\Resources\DnaMatchingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDnaMatching extends EditRecord
{
    protected static string $resource = DnaMatchingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
