<?php

namespace App\Filament\App\Resources\ChecklistTemplateResource\Pages;

use App\Filament\App\Resources\ChecklistTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChecklistTemplate extends CreateRecord
{
    #[\Override]
    protected static string $resource = ChecklistTemplateResource::class;

    #[\Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }

    #[\Override]
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}