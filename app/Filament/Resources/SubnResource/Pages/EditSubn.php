<?php

namespace App\Filament\Resources\SubnResource\Pages;

use App\Filament\Resources\SubnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubn extends EditRecord
{
    protected static string $resource = SubnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
