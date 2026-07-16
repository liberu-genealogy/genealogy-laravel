<?php

namespace App\Filament\App\Widgets;

use App\Models\Family;
use App\Models\Person;
use Filament\Widgets\Widget;

class RecentActivityWidget extends Widget
{
    #[\Override]
    protected string $view = 'filament.app.widgets.recent-activity';

    #[\Override]
    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    #[\Override]
    protected static ?int $sort = 3;

    #[\Override]
    public function getViewData(): array
    {
        $recentPeople = Person::latest()
            ->limit(5)
            ->get()
            ->map(fn ($person) => [
                'type' => 'person',
                'title' => $person->fullname(),
                'subtitle' => 'Person added',
                'date' => $person->created_at,
                'icon' => 'heroicon-o-user-plus',
                'color' => 'success',
            ]);

        $recentFamilies = Family::latest()
            ->limit(3)
            ->get()
            ->map(fn ($family) => [
                'type' => 'family',
                'title' => 'Family #'.$family->id,
                'subtitle' => 'Family created',
                'date' => $family->created_at,
                'icon' => 'heroicon-o-home',
                'color' => 'info',
            ]);

        $activities = $recentPeople->merge($recentFamilies)
            ->sortByDesc('date')
            ->take(8);

        return [
            'activities' => $activities,
        ];
    }
}
