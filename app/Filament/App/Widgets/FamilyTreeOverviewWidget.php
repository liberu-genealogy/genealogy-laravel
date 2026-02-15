<?php

namespace App\Filament\App\Widgets;

use App\Models\Person;
use Filament\Widgets\Widget;

class FamilyTreeOverviewWidget extends Widget
{
    protected string $view = 'filament.app.widgets.family-tree-overview';

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
        // Calculate actual depth by finding the deepest ancestor chain
        $maxDepth = 0;
        $people = Person::whereNotNull('child_in_family_id')->with('childInFamily')->get();
        
        foreach ($people as $person) {
            $depth = $this->calculatePersonDepth($person);
            $maxDepth = max($maxDepth, $depth);
        }
        
        return max($maxDepth, 1);
    }
    
    private function calculatePersonDepth(Person $person, int $currentDepth = 1, array &$visited = []): int
    {
        // Prevent infinite loops in case of data issues
        if (in_array($person->id, $visited)) {
            return $currentDepth;
        }
        
        $visited[] = $person->id;
        
        if (!$person->childInFamily) {
            return $currentDepth;
        }
        
        $maxParentDepth = $currentDepth;
        
        if ($person->childInFamily->husband) {
            $fatherDepth = $this->calculatePersonDepth($person->childInFamily->husband, $currentDepth + 1, $visited);
            $maxParentDepth = max($maxParentDepth, $fatherDepth);
        }
        
        if ($person->childInFamily->wife) {
            $motherDepth = $this->calculatePersonDepth($person->childInFamily->wife, $currentDepth + 1, $visited);
            $maxParentDepth = max($maxParentDepth, $motherDepth);
        }
        
        return $maxParentDepth;
    }
}
