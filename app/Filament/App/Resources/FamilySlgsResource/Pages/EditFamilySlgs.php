<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\FamilySlgsResource\Pages;

use App\Filament\App\Resources\FamilySlgsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFamilySlgs extends EditRecord
{
    #[\Override]
    protected static string $resource = FamilySlgsResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
