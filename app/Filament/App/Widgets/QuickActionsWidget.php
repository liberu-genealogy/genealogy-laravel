<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected string $view = 'filament.app.widgets.quick-actions';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'label' => 'Add Person',
                    'icon' => 'heroicon-o-user-plus',
                    'url' => route('filament.app.resources.people.create'),
                    'color' => 'success',
                    'description' => 'Add a new family member'
                ],
                [
                    'label' => 'Add Family',
                    'icon' => 'heroicon-o-home',
                    'url' => route('filament.app.resources.families.create'),
                    'color' => 'info',
                    'description' => 'Create a new family unit'
                ],
                [
                    'label' => 'View Pedigree',
                    'icon' => 'heroicon-o-chart-bar',
                    'url' => route('filament.app.pages.pedigree-chart'),
                    'color' => 'warning',
                    'description' => 'Explore your family tree'
                ],
                [
                    'label' => 'Import GEDCOM',
                    'icon' => 'heroicon-o-arrow-up-tray',
                    'url' => route('filament.app.resources.gedcom.create'),
                    'color' => 'primary',
                    'description' => 'Import genealogy data'
                ],
                [
                    'label' => 'DNA Analysis',
                    'icon' => 'heroicon-o-beaker',
                    'url' => route('filament.app.resources.dna.index'),
                    'color' => 'purple',
                    'description' => 'Analyze DNA matches'
                ],
                [
                    'label' => 'Add Media',
                    'icon' => 'heroicon-o-photo',
                    'url' => route('filament.app.resources.media-objects.create'),
                    'color' => 'pink',
                    'description' => 'Upload photos & documents'
                ],
            ]
        ];
    }
}
