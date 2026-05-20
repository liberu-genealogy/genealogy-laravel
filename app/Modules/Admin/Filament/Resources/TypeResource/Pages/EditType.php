<?php

namespace App\Modules\Admin\Filament\Resources\TypeResource\Pages;

use Filament\Actions\DeleteAction;
use App\Modules\Admin\Filament\Resources\TypeResource;
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