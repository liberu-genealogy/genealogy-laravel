/**
 * This file contains the DAbovilleReportPage class, which is responsible for rendering the D'Aboville report page in the Filament admin panel.
 * The D'Aboville report is a specific type of genealogical report used within the application.
 */
<?php

namespace App\Filament\Pages;

use App\Filament\Pages\CustomFilamentBasePage;
/**
 * Renders the D'Aboville report page within the Filament admin panel.
 * This class extends the CustomFilamentBasePage to inherit common properties and methods for custom Filament pages.
 */
class DAbovilleReportPage extends CustomFilamentBasePage
{
    protected static string $view = 'livewire.daboville-report';

//    public function render(): \Illuminate\Contracts\Support\Renderable
//    {
//        return \Livewire::mount(static::$view);
//    }

    public function mount(): void
    {
        Livewire::mount(static::$view);
    }
}
