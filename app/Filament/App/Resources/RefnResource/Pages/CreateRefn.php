<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\RefnResource\Pages;

use App\Filament\App\Resources\RefnResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRefn extends CreateRecord
{
    #[\Override]
    protected static string $resource = RefnResource::class;
}
