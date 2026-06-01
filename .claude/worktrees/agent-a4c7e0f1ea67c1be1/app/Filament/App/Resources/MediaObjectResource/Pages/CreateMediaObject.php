<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\MediaObjectResource\Pages;

use App\Filament\App\Resources\MediaObjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMediaObject extends CreateRecord
{
    #[\Override]
    protected static string $resource = MediaObjectResource::class;
}
