<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Gedcom\Parser\Fam\Slgs\Stat as SlgsStat;

class NewDabovilleReportWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('DabovilleReport', '10k')
                ->label(__('Daboville Report'))
                ->value('10k')
                ->description('Total Daboville Family Report')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('DeVilliersReport', '7,500,000')
                ->label(__('DeVilliers Report'))
                ->value('3,000,000')
                ->description('Total DeVilliers Family Report')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

                Stat::make('HenryReport', '3,000,000')
                ->label(__('Henry Report'))
                ->value('7,500,000')
                ->description('Total Henry Reports Found')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }

    public function getGridColumns(): int
    {
        return 6; // Set the number of columns in the grid
    }
}

