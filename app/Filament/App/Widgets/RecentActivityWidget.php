<?php

namespace App\Filament\App\Widgets;

use App\Models\Person;
use App\Models\Family;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class RecentActivityWidget extends Widget
{
    protected string $view = 'filament.app.widgets.recent-activity';

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    protected static ?int $sort = 3;

    public function getViewData(): array
    {
        $recentPeople = Person::latest()
            ->limit(5)
            ->get()
            ->map(function ($person) {
                return [
                    'type' => 'person',
                    'title' => $person->fullname(),
                    'subtitle' => 'Person added',
                    'date' => $person->created_at,
                    'icon' => 'heroicon-o-user-plus',
                    'color' => 'success',
                ];
            });

        $recentFamilies = Family::latest()
            ->limit(3)
            ->get()
            ->map(function ($family) {
                return [
                    'type' => 'family',
                    'title' => 'Family #' . $family->id,
                    'subtitle' => 'Family created',
                    'date' => $family->created_at,
                    'icon' => 'heroicon-o-home',
                    'color' => 'info',
                ];
            });

        $activities = $recentPeople->merge($recentFamilies)
            ->sortByDesc('date')
            ->take(8);

        return [
            'activities' => $activities
        ];
    }
}
