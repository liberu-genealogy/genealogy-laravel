<?php

namespace App\Filament\Resources\PersonSubmResource\Pages;

use App\Filament\Resources\PersonSubmResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonSubm extends EditRecord
{
    protected static string $resource = PersonSubmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
