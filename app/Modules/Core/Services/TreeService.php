<?php

namespace App\Modules\Core\Services;

use App\Models\Person;
use App\Models\Family;
use Illuminate\Support\Collection;

class TreeService
{
    /**
     * Generate family tree data for a person.
     */
    public function generateTreeData(Person $person, int $generations = 3): array
    {
        return [
            'person' => $this->formatPersonData($person),
            'ancestors' => $this->getAncestors($person, $generations),
            'descendants' => $this->getDescendants($person, $generations),
        ];
    }

    /**
     * Get ancestors for a person.
     */
    public function getAncestors(Person $person, int $generations): array
    {
        if ($generations <= 0) {
            return [];
        }

        $ancestors = [];
        $family = $person->childInFamily;

        if ($family) {
            if ($family->husband) {
                $ancestors['father'] = [
                    'person' => $this->formatPersonData($family->husband),
                    'ancestors' => $this->getAncestors($family->husband, $generations - 1),
                ];
            }

            if ($family->wife) {
                $ancestors['mother'] = [
                    'person' => $this->formatPersonData($family->wife),
                    'ancestors' => $this->getAncestors($family->wife, $generations - 1),
                ];
            }
        }

        return $ancestors;
    }

    /**
     * Get descendants for a person.
     */
    public function getDescendants(Person $person, int $generations): array
    {
        if ($generations <= 0) {
            return [];
        }

        $descendants = [];
        $families = $person->familiesAsHusband->merge($person->familiesAsWife);

        foreach ($families as $family) {
            $children = Person::where('child_in_family_id', $family->id)->get();
            
            foreach ($children as $child) {
                $descendants[] = [
                    'person' => $this->formatPersonData($child),
                    'descendants' => $this->getDescendants($child, $generations - 1),
                ];
            }
        }

        return $descendants;
    }

    /**
     * Format person data for tree display.
     */
    protected function formatPersonData(Person $person): array
    {
        return [
            'id' => $person->id,
            'name' => $person->fullname(),
            'given_name' => $person->givn,
            'surname' => $person->surn,
            'sex' => $person->sex,
            'birth_date' => $person->birthday?->format('Y-m-d'),
            'death_date' => $person->deathday?->format('Y-m-d'),
            'is_living' => !$person->deathday,
        ];
    }

    /**
     * Calculate relationship between two persons.
     */
    public function calculateRelationship(Person $person1, Person $person2): ?string
    {
        // Implementation for relationship calculation
        // This is a complex algorithm that would need detailed implementation
        return null;
    }

    /**
     * Get all living descendants of a person.
     */
    public function getLivingDescendants(Person $person): Collection
    {
        return $this->getAllDescendants($person)
            ->filter(fn($descendant) => !$descendant->deathday);
    }

    /**
     * Get all descendants of a person (recursive).
     */
    protected function getAllDescendants(Person $person): Collection
    {
        $descendants = collect();
        $families = $person->familiesAsHusband->merge($person->familiesAsWife);

        foreach ($families as $family) {
            $children = Person::where('child_in_family_id', $family->id)->get();
            
            foreach ($children as $child) {
                $descendants->push($child);
                $descendants = $descendants->merge($this->getAllDescendants($child));
            }
        }

        return $descendants;
    }
}