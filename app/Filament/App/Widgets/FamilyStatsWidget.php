<?php

namespace App\Filament\App\Widgets;

use App\Models\Person;
use App\Models\Family;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FamilyStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPeople = Person::count();
        $totalFamilies = Family::count();
        $livingPeople = Person::whereNull('deathday')->count();
        $recentlyAdded = Person::where('created_at', '>=', now()->subDays(30))->count();

        return [
            Stat::make('Total People', $totalPeople)
                ->description('Individuals in your family tree')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Families', $totalFamilies)
                ->description('Family units recorded')
                ->descriptionIcon('heroicon-m-home')
                ->color('info')
                ->chart([15, 4, 10, 2, 12, 4, 12]),

            Stat::make('Living People', $livingPeople)
                ->description('Currently living family members')
                ->descriptionIcon('heroicon-m-heart')
                ->color('warning')
                ->chart([2, 10, 1, 22, 15, 4, 17]),

            Stat::make('Recently Added', $recentlyAdded)
                ->description('New entries this month')
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
        ];
    }
}