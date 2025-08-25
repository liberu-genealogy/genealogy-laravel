<?php

namespace App\Filament\App\Widgets;

use App\Models\Person;
use Filament\Widgets\Widget;

class FamilyTreeOverviewWidget extends Widget
{
    protected static string $view = 'filament.app.widgets.family-tree-overview';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 4;

    public function getViewData(): array
    {
        // Get a sample of people for the mini tree view
        $rootPerson = Person::whereNotNull('child_in_family_id')
            ->with(['childInFamily.husband', 'childInFamily.wife'])
            ->first();

        $generations = [];
        if ($rootPerson) {
            // Build a simple 3-generation view
            $generations = $this->buildMiniTree($rootPerson, 3);
        }

        return [
            'rootPerson' => $rootPerson,
            'generations' => $generations,
            'totalPeople' => Person::count(),
            'totalGenerations' => $this->calculateGenerations(),
        ];
    }

    private function buildMiniTree($person, $maxGenerations, $currentGen = 1): array
    {
        if (!$person || $currentGen > $maxGenerations) {
            return [];
        }

        $tree = [
            'person' => $person,
            'generation' => $currentGen,
            'parents' => []
        ];

        if ($person->childInFamily) {
            $family = $person->childInFamily;
            if ($family->husband) {
                $tree['parents']['father'] = $this->buildMiniTree($family->husband, $maxGenerations, $currentGen + 1);
            }
            if ($family->wife) {
                $tree['parents']['mother'] = $this->buildMiniTree($family->wife, $maxGenerations, $currentGen + 1);
            }
        }

        return $tree;
    }

    private function calculateGenerations(): int
    {
        // Simple calculation - could be more sophisticated
        return Person::selectRaw('MAX(CASE WHEN deathday IS NULL THEN 0 ELSE YEAR(CURDATE()) - YEAR(birthday) END) / 25 as generations')
            ->value('generations') ?? 1;
    }
}