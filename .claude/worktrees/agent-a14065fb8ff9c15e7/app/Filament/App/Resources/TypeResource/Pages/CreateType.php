<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\TypeResource\Pages;

use App\Filament\App\Resources\TypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateType extends CreateRecord
{
    #[\Override]
    protected static string $resource = TypeResource::class;
}
