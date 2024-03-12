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
