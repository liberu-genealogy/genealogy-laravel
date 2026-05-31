<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceRefEvenResource\Pages;

use App\Filament\App\Resources\SourceRefEvenResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSourceRefEven extends CreateRecord
{
    #[\Override]
    protected static string $resource = SourceRefEvenResource::class;
}
