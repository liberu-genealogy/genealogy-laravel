<?php

namespace App\Filament\App\Resources\SourceRefEvenResource\Pages;

use App\Filament\App\Resources\SourceRefEvenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSourceRefEven extends EditRecord
{
    protected static string $resource = SourceRefEvenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
