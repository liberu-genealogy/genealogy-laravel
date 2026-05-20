<?php

namespace App\Filament\App\Resources\SourceRepoResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\SourceRepoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSourceRepo extends EditRecord
{
    protected static string $resource = SourceRepoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
