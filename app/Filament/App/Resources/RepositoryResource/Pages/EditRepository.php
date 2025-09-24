<?php

namespace App\Filament\App\Resources\RepositoryResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\RepositoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRepository extends EditRecord
{
    protected static string $resource = RepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
