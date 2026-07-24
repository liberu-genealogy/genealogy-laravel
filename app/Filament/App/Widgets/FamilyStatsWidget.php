<?php

namespace App\Filament\App\Widgets;

use App\Models\Family;
use App\Models\Person;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FamilyStatsWidget extends BaseWidget
{
    #[\Override]
    protected function getStats(): array
    {
        $totalPeople = Person::count();
        $totalFamilies = Family::count();
        $livingPeople = Person::whereNull('deathday')->count();
        $recentlyAdded = Person::where('created_at', '>=', now()->subDays(30))->count();

        return [
            // No ->chart(): the previous sparklines were hardcoded fabricated arrays. Real
            // per-period trend is an optional follow-up (see people-1618 map "Not yet specified").
            Stat::make('Total People', $totalPeople)
                ->description('Individuals in your family tree')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Families', $totalFamilies)
                ->description('Family units recorded')
                ->descriptionIcon('heroicon-m-home')
                ->color('info'),

            Stat::make('Living People', $livingPeople)
                ->description('Currently living family members')
                ->descriptionIcon('heroicon-m-heart')
                ->color('warning'),

            Stat::make('Recently Added', $recentlyAdded)
                ->description('New entries this month')
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('primary'),
        ];
    }
}
