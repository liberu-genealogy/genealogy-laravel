/**
 * This file contains the DAbovilleReportPage class, which is responsible for rendering the D'Aboville report page in the Filament admin panel.
 * The D'Aboville report is a specific type of genealogical report used within the application.
 */
<?php

namespace App\Filament\Pages;

use App\Filament\Pages\CustomFilamentBasePage;
class DAbovilleReportPage extends CustomFilamentBasePage
{
    protected static string $view = 'livewire.daboville-report';

    public function render()
    {
        return view(static::$view);
    }
}
