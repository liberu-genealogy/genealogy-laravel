<?php

namespace App\Filament\App\Resources\PersonSubmResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\PersonSubmResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonSubm extends EditRecord
{
    protected static string $resource = PersonSubmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
