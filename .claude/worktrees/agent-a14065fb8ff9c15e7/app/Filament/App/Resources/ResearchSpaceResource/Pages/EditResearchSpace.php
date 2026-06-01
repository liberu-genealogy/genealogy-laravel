<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\ResearchSpaceResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\ResearchSpaceResource;

class EditResearchSpace extends EditRecord
{
    #[\Override]
    protected static string $resource = ResearchSpaceResource::class;
}
