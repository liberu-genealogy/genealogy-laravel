<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceDataEvenResource\Pages;

use App\Filament\App\Resources\SourceDataEvenResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSourceDataEven extends CreateRecord
{
    #[\Override]
    protected static string $resource = SourceDataEvenResource::class;
}
