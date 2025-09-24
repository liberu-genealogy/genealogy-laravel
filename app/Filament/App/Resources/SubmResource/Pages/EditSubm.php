<?php

namespace App\Filament\App\Resources\SubmResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\SubmResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubm extends EditRecord
{
    protected static string $resource = SubmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
