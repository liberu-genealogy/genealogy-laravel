<?php

namespace App\Filament\App\Resources\TypeResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\TypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditType extends EditRecord
{
    protected static string $resource = TypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
