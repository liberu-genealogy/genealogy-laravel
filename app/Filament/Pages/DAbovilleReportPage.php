/**
 * Defines the D'Aboville Report Page within the genealogy application.
 * 
 * This page class extends the custom Filament base page to render the D'Aboville report using Livewire.
 * It is part of the application's reporting functionality, specifically tailored for genealogical data visualization.
 */
<?php

namespace App\Filament\Pages;

use Livewire\Livewire;

class DAbovilleReportPage extends CustomFilamentBasePage
{
    protected static string $view = 'livewire.daboville-report';
    protected static ?string $title = 'DAboville Report';
    protected static ?string $navigationIcon = 'heroicon-o-document-report';

    public function getTitle(): string
    {
        return static::$title;
    }

    public static function getNavigationIcon(): string
    {
        return static::$navigationIcon;
    }

//    public function render(): \Illuminate\Contracts\Support\Renderable
//    {
//        return \Livewire::mount(static::$view);
//    }

    public function mount(): void
    {
        Livewire::mount(static::$view);
    }
}
/**
 * Renders the D'Aboville report page.
 * 
 * This function mounts the Livewire component specified by the static `$view` property,
 * facilitating the display of the D'Aboville report within the application.
 * 
 * @return \Illuminate\Contracts\Support\Renderable The rendered view of the D'Aboville report page.
 */
