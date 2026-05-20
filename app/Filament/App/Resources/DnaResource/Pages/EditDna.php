<?php

namespace App\Filament\App\Resources\DnaResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\DnaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDna extends EditRecord
{
    protected static string $resource = DnaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
