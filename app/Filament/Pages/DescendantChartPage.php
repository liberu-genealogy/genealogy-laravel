<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\Livewire;

class DescendantChartPage extends Page
{
    protected static string $view = 'descendant-chart-page';
    
    protected static ?string $title = 'Descendant Chart';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Charts';
}
