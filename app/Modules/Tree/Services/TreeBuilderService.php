<?php

namespace App\Modules\Tree\Services;

use App\Models\Person;
use App\Models\Family;
use Illuminate\Support\Collection;

class TreeBuilderService
{
    /**
     * Build a complete family tree for a person.
     */
    public function buildFamilyTree(Person $rootPerson, array $options = []): array
    {
        $generations = $options['generations'] ?? 4;
        $includeSpouses = $options['include_spouses'] ?? true;
        $includeSiblings = $options['include_siblings'] ?? false;

        return [
            'root_person' => $this->formatPersonNode($rootPerson),
            'ancestors' => $this->buildAncestorTree($rootPerson, $generations),
            'descendants' => $this->buildDescendantTree($rootPerson, $generations),
            'metadata' => [
                'generations' => $generations,
                'total_persons' => $this->countTreePersons($rootPerson, $generations),
                'build_date' => now()->toISOString(),
            ],
        ];
    }

    /**
     * Build ancestor tree (parents, grandparents, etc.).
     */
    public function buildAncestorTree(Person $person, int $generations): array
    {
        if ($generations <= 0) {
            return [];
        }

        $ancestors = [];
        $family = $person->childInFamily;

        if ($family) {
            if ($family->husband) {
                $ancestors['father'] = [
                    'person' => $this->formatPersonNode($family->husband),
                    'ancestors' => $this->buildAncestorTree($family->husband, $generations - 1),
                ];
            }

            if ($family->wife) {
                $ancestors['mother'] = [
                    'person' => $this->formatPersonNode($family->wife),
                    'ancestors' => $this->buildAncestorTree($family->wife, $generations - 1),
                ];
            }
        }

        return $ancestors;
    }

    /**
     * Build descendant tree (children, grandchildren, etc.).
     */
    public function buildDescendantTree(Person $person, int $generations): array
    {
        if ($generations <= 0) {
            return [];
        }

        $descendants = [];
        $families = $person->familiesAsHusband->merge($person->familiesAsWife);

        foreach ($families as $family) {
            $children = Person::where('child_in_family_id', $family->id)
                ->orderBy('birthday')
                ->get();

            $familyNode = [
                'family_id' => $family->id,
                'spouse' => null,
                'children' => [],
            ];

            // Add spouse information
            if ($person->sex === 'M' && $family->wife) {
                $familyNode['spouse'] = $this->formatPersonNode($family->wife);
            } elseif ($person->sex === 'F' && $family->husband) {
                $familyNode['spouse'] = $this->formatPersonNode($family->husband);
            }

            // Add children
            foreach ($children as $child) {
                $familyNode['children'][] = [
                    'person' => $this->formatPersonNode($child),
                    'descendants' => $this->buildDescendantTree($child, $generations - 1),
                ];
            }

            $descendants[] = $familyNode;
        }

        return $descendants;
    }

    /**
     * Build a pedigree chart (ancestors only).
     */
    public function buildPedigreeChart(Person $person, int $generations = 4): array
    {
        return [
            'type' => 'pedigree',
            'root_person' => $this->formatPersonNode($person),
            'chart_data' => $this->buildPedigreeData($person, $generations, 1),
            'metadata' => [
                'generations' => $generations,
                'chart_type' => 'pedigree',
                'build_date' => now()->toISOString(),
            ],
        ];
    }

    /**
     * Build pedigree data recursively.
     */
    protected function buildPedigreeData(Person $person, int $maxGenerations, int $currentGeneration): array
    {
        $data = [
            'generation' => $currentGeneration,
            'position' => pow(2, $currentGeneration - 1),
            'person' => $this->formatPersonNode($person),
        ];

        if ($currentGeneration < $maxGenerations) {
            $family = $person->childInFamily;
            if ($family) {
                $data['parents'] = [];
                
                if ($family->husband) {
                    $data['parents']['father'] = $this->buildPedigreeData(
                        $family->husband, 
                        $maxGenerations, 
                        $currentGeneration + 1
                    );
                }
                
                if ($family->wife) {
                    $data['parents']['mother'] = $this->buildPedigreeData(
                        $family->wife, 
                        $maxGenerations, 
                        $currentGeneration + 1
                    );
                }
            }
        }

        return $data;
    }

    /**
     * Build a descendant chart.
     */
    public function buildDescendantChart(Person $person, int $generations = 4): array
    {
        return [
            'type' => 'descendant',
            'root_person' => $this->formatPersonNode($person),
            'chart_data' => $this->buildDescendantData($person, $generations, 1),
            'metadata' => [
                'generations' => $generations,
                'chart_type' => 'descendant',
                'build_date' => now()->toISOString(),
            ],
        ];
    }

    /**
     * Build descendant data recursively.
     */
    protected function buildDescendantData(Person $person, int $maxGenerations, int $currentGeneration): array
    {
        $data = [
            'generation' => $currentGeneration,
            'person' => $this->formatPersonNode($person),
            'families' => [],
        ];

        if ($currentGeneration < $maxGenerations) {
            $families = $person->familiesAsHusband->merge($person->familiesAsWife);
            
            foreach ($families as $family) {
                $familyData = [
                    'family_id' => $family->id,
                    'spouse' => null,
                    'children' => [],
                ];

                // Add spouse
                if ($person->sex === 'M' && $family->wife) {
                    $familyData['spouse'] = $this->formatPersonNode($family->wife);
                } elseif ($person->sex === 'F' && $family->husband) {
                    $familyData['spouse'] = $this->formatPersonNode($family->husband);
                }

                // Add children
                $children = Person::where('child_in_family_id', $family->id)
                    ->orderBy('birthday')
                    ->get();

                foreach ($children as $child) {
                    $familyData['children'][] = $this->buildDescendantData(
                        $child, 
                        $maxGenerations, 
                        $currentGeneration + 1
                    );
                }

                $data['families'][] = $familyData;
            }
        }

        return $data;
    }

    /**
     * Format person data for tree nodes.
     */
    protected function formatPersonNode(Person $person): array
    {
        return [
            'id' => $person->id,
            'name' => $person->fullname(),
            'given_name' => $person->givn,
            'surname' => $person->surn,
            'sex' => $person->sex,
            'birth_date' => $person->birthday?->format('Y-m-d'),
            'birth_year' => $person->birthday?->year,
            'death_date' => $person->deathday?->format('Y-m-d'),
            'death_year' => $person->deathday?->year,
            'is_living' => !$person->deathday,
            'age' => $this->calculateAge($person),
            'lifespan' => $this->formatLifespan($person),
        ];
    }

    /**
     * Calculate person's age.
     */
    protected function calculateAge(Person $person): ?int
    {
        if (!$person->birthday) {
            return null;
        }

        $endDate = $person->deathday ?? now();
        return $person->birthday->diffInYears($endDate);
    }

    /**
     * Format person's lifespan.
     */
    protected function formatLifespan(Person $person): string
    {
        $birth = $person->birthday?->year ?? '?';
        $death = $person->deathday?->year ?? ($person->birthday ? 'living' : '?');
        
        return "({$birth}-{$death})";
    }

    /**
     * Count total persons in tree.
     */
    protected function countTreePersons(Person $rootPerson, int $generations): int
    {
        // This would recursively count all persons in the tree
        // Implementation would depend on specific counting requirements
        return 1; // Placeholder
    }

    /**
     * Get siblings of a person.
     */
    public function getSiblings(Person $person): Collection
    {
        if (!$person->child_in_family_id) {
            return collect();
        }

        return Person::where('child_in_family_id', $person->child_in_family_id)
            ->where('id', '!=', $person->id)
            ->orderBy('birthday')
            ->get();
    }

    /**
     * Get all ancestors of a person (flattened list).
     */
    public function getAllAncestors(Person $person, int $maxGenerations = 10): Collection
    {
        $ancestors = collect();
        $this->collectAncestors($person, $ancestors, $maxGenerations);
        return $ancestors;
    }

    /**
     * Recursively collect ancestors.
     */
    protected function collectAncestors(Person $person, Collection $ancestors, int $remainingGenerations): void
    {
        if ($remainingGenerations <= 0) {
            return;
        }

        $family = $person->childInFamily;
        if ($family) {
            if ($family->husband && !$ancestors->contains('id', $family->husband->id)) {
                $ancestors->push($family->husband);
                $this->collectAncestors($family->husband, $ancestors, $remainingGenerations - 1);
            }

            if ($family->wife && !$ancestors->contains('id', $family->wife->id)) {
                $ancestors->push($family->wife);
                $this->collectAncestors($family->wife, $ancestors, $remainingGenerations - 1);
            }
        }
    }

    /**
     * Get all descendants of a person (flattened list).
     */
    public function getAllDescendants(Person $person, int $maxGenerations = 10): Collection
    {
        $descendants = collect();
        $this->collectDescendants($person, $descendants, $maxGenerations);
        return $descendants;
    }

    /**
     * Recursively collect descendants.
     */
    protected function collectDescendants(Person $person, Collection $descendants, int $remainingGenerations): void
    {
        if ($remainingGenerations <= 0) {
            return;
        }

        $families = $person->familiesAsHusband->merge($person->familiesAsWife);
        
        foreach ($families as $family) {
            $children = Person::where('child_in_family_id', $family->id)->get();
            
            foreach ($children as $child) {
                if (!$descendants->contains('id', $child->id)) {
                    $descendants->push($child);
                    $this->collectDescendants($child, $descendants, $remainingGenerations - 1);
                }
            }
        }
    }
}