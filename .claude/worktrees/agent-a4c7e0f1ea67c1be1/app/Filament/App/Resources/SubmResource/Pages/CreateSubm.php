<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SubmResource\Pages;

use App\Filament\App\Resources\SubmResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSubm extends CreateRecord
{
    #[\Override]
    protected static string $resource = SubmResource::class;
}
