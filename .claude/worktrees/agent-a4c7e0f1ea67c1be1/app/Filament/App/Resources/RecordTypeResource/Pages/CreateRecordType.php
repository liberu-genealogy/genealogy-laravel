<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\RecordTypeResource\Pages;

use App\Filament\App\Resources\RecordTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRecordType extends CreateRecord
{
    #[\Override]
    protected static string $resource = RecordTypeResource::class;
}
