<?php

namespace App\Filament\App\Resources\DnaMatchingResource\Pages;

use App\Filament\App\Resources\DnaMatchingResource;
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
