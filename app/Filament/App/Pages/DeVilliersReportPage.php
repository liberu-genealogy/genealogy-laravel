<?php

namespace App\Filament\App\Pages;

use UnitEnum;
use BackedEnum;
use App\Filament\Resources\GedcomResource as ResourcesGedcomResource;
use App\Filament\Resources\MediaObjectResource as ResourcesMediaObjectResource;
use App\Filament\Resources\NoteResource as ResourcesNoteResource;
use App\Filament\Resources\PublicationResource as ResourcesPublicationResource;
use App\Filament\Resources\RepositoryResource as ResourcesRepositoryResource;
use App\Filament\Resources\SourceDataResource as ResourcesSourceDataResource;
use App\Filament\Resources\TypeResource as ResourcesTypeResource;
use Filament\Pages\Page;
use Livewire\Livewire;

class DeVilliersReportPage extends Page
{
    protected string $view = 'de-villiers-report-page';

    protected static ?string $title = 'Devilliers Report';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static UnitEnum|string|null $navigationGroup = 'Reports';

    //  public function mount(): void
    // {
    //     Livewire::mount('DeVilliersReportWidget');
    // }

    public function resources(): array
    {
        return [
            ResourcesTypeResource::class,
            // ResourcesGedcomResource::class,
            ResourcesMediaObjectResource::class,
            ResourcesNoteResource::class,
            ResourcesRepositoryResource::class,
            ResourcesPublicationResource::class,
            ResourcesSourceDataResource::class,
            ResourcesPublicationResource::class,
        ];
    }
}
