<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SubnResource\Pages;

use App\Filament\App\Resources\SubnResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSubn extends CreateRecord
{
    #[\Override]
    protected static string $resource = SubnResource::class;
}
