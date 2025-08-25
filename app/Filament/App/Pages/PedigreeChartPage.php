<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class PedigreeChartPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    
    protected static ?string $navigationLabel = 'Pedigree Chart';
    
    protected static ?string $navigationGroup = 'Charts & Reports';
    
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.app.pages.pedigree-chart-page';

    protected static ?string $title = 'Pedigree Chart';

    public function getWidgets(): array
    {
        return [
            \App\Http\Livewire\PedigreeChartWidget::class,
        ];
    }
}