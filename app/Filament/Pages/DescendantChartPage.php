<?php

/**
 * Class DescendantChartPage
 * 
 * Manages the presentation and functionality of the Descendant Chart page in the genealogy application.
 * Utilizes Livewire for dynamic content rendering.
 */

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\Livewire;

class DescendantChartPage extends Page
{
    protected static string $view = 'filament.pages.descendant-chart';
    protected static ?string $resource = null;
    protected static ?string $title = 'Descendant Chart';
    protected ?string $navigationIcon = 'heroicon-o-chart-bar';

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getNavigationIcon(): string
    {
        return $this->navigationIcon;
    }

    public function mount(): void
    {
        Livewire::mount(\App\Http\Livewire\DescendantChartComponent::class);
    }
}
