<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\ChecklistTemplateResource\Pages;

use App\Filament\App\Resources\ChecklistTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditChecklistTemplate extends EditRecord
{
    #[\Override]
    protected static string $resource = ChecklistTemplateResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
