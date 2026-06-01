<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PublicationResource\Pages;

use App\Filament\App\Resources\PublicationResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePublication extends CreateRecord
{
    #[\Override]
    protected static string $resource = PublicationResource::class;
}
