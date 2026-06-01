<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\DnaMatchingResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\DnaMatchingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDnaMatching extends EditRecord
{
    #[\Override]
    protected static string $resource = DnaMatchingResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
