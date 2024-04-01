<?php

namespace App\Filament\Resources\NewRepositoryResource\Pages;

use App\Filament\Resources\NewRepositoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewRepository extends EditRecord
{
    protected static string $resource = NewRepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
