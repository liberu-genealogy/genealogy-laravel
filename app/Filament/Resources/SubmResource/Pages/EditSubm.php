<?php

namespace App\Filament\Resources\SubmResource\Pages;

use App\Filament\Resources\SubmResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubm extends EditRecord
{
    protected static string $resource = SubmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
