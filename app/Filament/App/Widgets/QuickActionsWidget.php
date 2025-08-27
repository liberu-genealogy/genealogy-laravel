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
                    'url' => \Filament\Facades\Filament::getUrl() . '/people/create',
                    'color' => 'success',
                    'description' => 'Add a new family member'
                ],
                [
                    'label' => 'Add Family',
                    'icon' => 'heroicon-o-home',
                    'url' => \Filament\Facades\Filament::getUrl() . '/families/create',
                    'color' => 'info',
                    'description' => 'Create a new family unit'
                ],
                [
                    'label' => 'View Pedigree',
                    'icon' => 'heroicon-o-chart-bar',
                    'url' => \Filament\Facades\Filament::getUrl() . '/pedigree-chart',
                    'color' => 'warning',
                    'description' => 'Explore your family tree'
                ],
                [
                    'label' => 'Import GEDCOM',
                    'icon' => 'heroicon-o-arrow-up-tray',
                    'url' => \Filament\Facades\Filament::getUrl() . '/gedcom/create',
                    'color' => 'primary',
                    'description' => 'Import genealogy data'
                ],
                [
                    'label' => 'DNA Analysis',
                    'icon' => 'heroicon-o-beaker',
                    'url' => \Filament\Facades\Filament::getUrl() . '/dna',
                    'color' => 'purple',
                    'description' => 'Analyze DNA matches'
                ],
                [
                    'label' => 'Add Media',
                    'icon' => 'heroicon-o-photo',
                    'url' => \Filament\Facades\Filament::getUrl() . '/media-objects/create',
                    'color' => 'pink',
                    'description' => 'Upload photos & documents'
                ],
            ]
        ];
    }
}
