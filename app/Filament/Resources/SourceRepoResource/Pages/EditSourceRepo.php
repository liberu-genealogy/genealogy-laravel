<?php

namespace App\Filament\Resources\SourceRepoResource\Pages;

use App\Filament\Resources\SourceRepoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSourceRepo extends EditRecord
{
    protected static string $resource = SourceRepoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
