<?php

namespace App\Filament\Resources\NewDnaResource\Pages;

use App\Filament\Resources\NewDnaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewDna extends EditRecord
{
    protected static string $resource = NewDnaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
