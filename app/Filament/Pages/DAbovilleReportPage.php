<?php
/**
 * Page component for generating the d'Aboville Report within the genealogy application.
 * This page utilizes Livewire for dynamic data handling.
 */

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DAbovilleReportPage extends Page
{
    protected static string $view = 'livewire.daboville-report';

    public function render()
    {
        return view(static::$view);
    }
}
    public function render()
    {
        return view(static::$view);
    }
}
