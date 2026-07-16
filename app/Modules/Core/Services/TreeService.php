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
     * Describe how $person1 is related to $person2 (e.g. "grandparent",
     * "sibling", "1st cousin once removed"). Framing is person1-relative:
     * calculateRelationship($grandpa, $grandchild) === 'grandparent'.
     *
     * Method: find the lowest common ancestor by intersecting each person's
     * ancestor set (id => generations up), then read the relationship off the
     * two generation distances d1, d2 to that ancestor.
     */
    public function calculateRelationship(Person $person1, Person $person2): string
    {
        $a = $this->ancestorDistances($person1);
        $b = $this->ancestorDistances($person2);

        // Lowest common ancestor = the shared ancestor with the smallest
        // combined generation distance.
        $d1 = $d2 = null;
        foreach (array_intersect_key($a, $b) as $id => $distA) {
            if ($d1 === null || $distA + $b[$id] < $d1 + $d2) {
                [$d1, $d2] = [$distA, $b[$id]];
            }
        }

        if ($d1 === null) {
            return 'no traceable relationship';
        }

        $min = min($d1, $d2);
        $diff = abs($d1 - $d2);

        // Direct line: one person is an ancestor of the other.
        if ($min === 0) {
            if ($diff === 0) {
                return 'self';
            }
            $g = $diff; // generations apart
            $up = $d2 === 0;   // person1 descends from person2
            $stem = $up ? 'grandchild' : 'grandparent';
            if ($g === 1) {
                return $up ? 'child' : 'parent';
            }

            return str_repeat('great-', $g - 2).$stem;
        }

        // Siblings and the aunt/uncle <-> niece/nephew ladder.
        if ($min === 1) {
            if ($diff === 0) {
                return 'sibling';
            }
            $g = max($d1, $d2); // >= 2

            return $d1 < $d2
                ? $this->collateralLabel($person1->sex, $g, true)   // person1 is the elder side
                : $this->collateralLabel($person1->sex, $g, false); // person1 is the younger side
        }

        // Cousins: min-1 gives the degree, the generation gap gives "removed".
        $label = $this->ordinal($min - 1).' cousin';

        return $diff === 0 ? $label : $label.' '.$this->timesRemoved($diff);
    }

    /**
     * Map every ancestor of $person (including $person at distance 0) to the
     * minimum number of generations up. BFS up parents; cap and visited-set
     * guard against runaway walks and cyclic (bad-data) trees.
     *
     * @return array<int,int> personId => generations
     */
    protected function ancestorDistances(Person $person, int $cap = 15): array
    {
        $distances = [];
        $visited = [$person->id => true];
        $queue = [[$person, 0]];

        while ($queue !== []) {
            [$current, $depth] = array_shift($queue);
            $distances[$current->id] = $depth;

            if ($depth >= $cap) {
                continue; // ponytail: hard cap so bad data can't loop forever
            }

            $family = $current->childInFamily;
            if (! $family) {
                continue;
            }

            foreach ([$family->husband, $family->wife] as $parent) {
                if ($parent && ! isset($visited[$parent->id])) {
                    $visited[$parent->id] = true;
                    $queue[] = [$parent, $depth + 1];
                }
            }
        }

        return $distances;
    }

    /**
     * aunt/uncle (g=2), grand-aunt/uncle (g=3), great-grand-... for the elder
     * side; niece/nephew mirror for the younger side. $sex picks the gendered
     * term, falling back to the combined form when unknown.
     */
    protected function collateralLabel(?string $sex, int $g, bool $elder): string
    {
        if ($elder) {
            $base = match ($sex) {
                Person::GENDER_MALE => 'uncle',
                Person::GENDER_FEMALE => 'aunt',
                default => 'aunt/uncle',
            };
        } else {
            $base = match ($sex) {
                Person::GENDER_MALE => 'nephew',
                Person::GENDER_FEMALE => 'niece',
                default => 'niece/nephew',
            };
        }

        return $g === 2 ? $base : str_repeat('great-', $g - 3).'grand-'.$base;
    }

    protected function ordinal(int $n): string
    {
        $suffix = match (true) {
            in_array($n % 100, [11, 12, 13], true) => 'th',
            $n % 10 === 1 => 'st',
            $n % 10 === 2 => 'nd',
            $n % 10 === 3 => 'rd',
            default => 'th',
        };

        return $n.$suffix;
    }

    protected function timesRemoved(int $n): string
    {
        return match ($n) {
            1 => 'once removed',
            2 => 'twice removed',
            3 => 'thrice removed',
            default => $n.' times removed',
        };
    }

    /**
     * Get all living descendants of a person.
     */
    public function getLivingDescendants(Person $person): Collection
    {
        return $this->getAllDescendants($person)
            ->filter(fn($descendant): bool => !$descendant->deathday);
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