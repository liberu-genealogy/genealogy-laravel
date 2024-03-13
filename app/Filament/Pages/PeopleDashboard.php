<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PeopleDashboard extends CustomFilamentBasePage
{
    protected static string $view = 'filament.pages.people-dashboard';
    
    protected static array $pages = [
        DeVilliersPamaReportPage::class,
    ];

    protected static ?string $title = 'People Dashboard';
}
use App\Filament\Pages\DeVilliersPamaReportPage;
use Filament\Pages\Page;
