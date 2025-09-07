<?php

namespace App\Filament\App\Resources\ChecklistTemplateResource\Pages;

use App\Filament\App\Resources\ChecklistTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChecklistTemplates extends ListRecords
{
    protected static string $resource = ChecklistTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}