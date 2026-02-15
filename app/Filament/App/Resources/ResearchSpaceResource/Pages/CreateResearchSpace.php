<?php

namespace App\Filament\App\Resources\ResearchSpaceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\App\Resources\ResearchSpaceResource;

class CreateResearchSpace extends CreateRecord
{
    protected static string $resource = ResearchSpaceResource::class;

    protected function beforeCreate(): void
    {
        // Automatically set owner/created_by to currently authenticated user
        $user = auth()->user();
        $this->data['owner_id'] = $user->id;
        $this->data['created_by'] = $user->id;
    }
}
