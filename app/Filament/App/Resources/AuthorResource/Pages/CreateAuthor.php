<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\AuthorResource\Pages;

use App\Filament\App\Resources\AuthorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAuthor extends CreateRecord
{
    #[\Override]
    protected static string $resource = AuthorResource::class;
}
