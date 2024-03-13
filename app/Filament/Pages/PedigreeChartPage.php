<?php

/**
 * Defines the Pedigree Chart page in the Filament admin panel.
 */

namespace App\Filament\Pages;

use Filament\Resources\ResourcePage;
use Filament\Pages\Page;

class PedigreeChartPage extends Page
{
    protected static string $view = 'filament.pages.pedigree-chart';

    protected static ?string $resource = null;

    protected static ?string $title = 'Pedigree Chart';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public function getTitle(): string
    {
        return static::$title;
    }

    public static function getNavigationIcon(): string
    {
        return static::$navigationIcon;
    }
}
        return static::$navigationIcon;
    }
}
