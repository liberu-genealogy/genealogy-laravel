<?php

namespace App\Modules\Family\Services;

use App\Models\Family;
use App\Models\Person;
use Illuminate\Support\Collection;

class FamilyService
{
    /**
     * Create a new family.
     */
    public function createFamily(array $data): Family
    {
        $family = Family::create([
            'husband_id' => $data['husband_id'] ?? null,
            'wife_id' => $data['wife_id'] ?? null,
            'marriage_date' => $data['marriage_date'] ?? null,
            'marriage_place' => $data['marriage_place'] ?? null,
            'divorce_date' => $data['divorce_date'] ?? null,
        ]);

        // Add marriage event if date provided
        if (!empty($data['marriage_date'])) {
            $this->addFamilyEvent($family, 'MARR', $data['marriage_date'], $data['marriage_place'] ?? '');
        }

        return $family;
    }

    /**
     * Add a child to a family.
     */
    public function addChildToFamily(Family $family, Person $child): void
    {
        $child->update(['child_in_family_id' => $family->id]);
    }

    /**
     * Remove a child from a family.
     */
    public function removeChildFromFamily(Person $child): void
    {
        $child->update(['child_in_family_id' => null]);
    }

    /**
     * Get family children.
     */
    public function getFamilyChildren(Family $family): Collection
    {
        return Person::where('child_in_family_id', $family->id)
            ->orderBy('birthday')
            ->get();
    }

    /**
     * Get family statistics.
     */
    public function getFamilyStatistics(): array
    {
        $totalFamilies = Family::count();
        $familiesWithChildren = Family::whereHas('children')->count();
        $averageChildren = $totalFamilies > 0 
            ? Person::whereNotNull('child_in_family_id')->count() / $totalFamilies 
            : 0;

        return [
            'total_families' => $totalFamilies,
            'families_with_children' => $familiesWithChildren,
            'families_without_children' => $totalFamilies - $familiesWithChildren,
            'average_children_per_family' => round($averageChildren, 2),
            'largest_family_size' => $this->getLargestFamilySize(),
        ];
    }

    /**
     * Get the size of the largest family.
     */
    protected function getLargestFamilySize(): int
    {
        return Family::withCount('children')
            ->orderBy('children_count', 'desc')
            ->first()
            ->children_count ?? 0;
    }

    /**
     * Add an event to a family.
     */
    public function addFamilyEvent(Family $family, string $type, string $date, string $place = ''): void
    {
        // Implementation would depend on having a FamilyEvent model
        // For now, we'll update the family record directly for marriage/divorce
        if ($type === 'MARR') {
            $family->update([
                'marriage_date' => $date,
                'marriage_place' => $place,
            ]);
        } elseif ($type === 'DIV') {
            $family->update([
                'divorce_date' => $date,
            ]);
        }
    }

    /**
     * Get family tree data.
     */
    public function getFamilyTreeData(Family $family): array
    {
        return [
            'family_id' => $family->id,
            'husband' => $family->husband ? [
                'id' => $family->husband->id,
                'name' => $family->husband->fullname(),
                'birth_date' => $family->husband->birthday?->format('Y-m-d'),
                'death_date' => $family->husband->deathday?->format('Y-m-d'),
            ] : null,
            'wife' => $family->wife ? [
                'id' => $family->wife->id,
                'name' => $family->wife->fullname(),
                'birth_date' => $family->wife->birthday?->format('Y-m-d'),
                'death_date' => $family->wife->deathday?->format('Y-m-d'),
            ] : null,
            'children' => $this->getFamilyChildren($family)->map(function ($child) {
                return [
                    'id' => $child->id,
                    'name' => $child->fullname(),
                    'sex' => $child->sex,
                    'birth_date' => $child->birthday?->format('Y-m-d'),
                    'death_date' => $child->deathday?->format('Y-m-d'),
                ];
            })->toArray(),
            'marriage_date' => $family->marriage_date,
            'marriage_place' => $family->marriage_place,
            'divorce_date' => $family->divorce_date,
        ];
    }

    /**
     * Search families.
     */
    public function searchFamilies(string $query): Collection
    {
        return Family::whereHas('husband', function ($q) use ($query) {
                $q->where('givn', 'LIKE', "%{$query}%")
                  ->orWhere('surn', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('wife', function ($q) use ($query) {
                $q->where('givn', 'LIKE', "%{$query}%")
                  ->orWhere('surn', 'LIKE', "%{$query}%");
            })
            ->with(['husband', 'wife'])
            ->get();
    }

    /**
     * Get families by surname.
     */
    public function getFamiliesBySurname(string $surname): Collection
    {
        return Family::whereHas('husband', function ($q) use ($surname) {
                $q->where('surn', $surname);
            })
            ->orWhereHas('wife', function ($q) use ($surname) {
                $q->where('surn', $surname);
            })
            ->with(['husband', 'wife', 'children'])
            ->get();
    }

    /**
     * Merge two family records.
     */
    public function mergeFamilies(Family $primaryFamily, Family $duplicateFamily): Family
    {
        // Transfer children from duplicate to primary family
        Person::where('child_in_family_id', $duplicateFamily->id)
            ->update(['child_in_family_id' => $primaryFamily->id]);

        // Merge family data
        if (empty($primaryFamily->marriage_date) && !empty($duplicateFamily->marriage_date)) {
            $primaryFamily->marriage_date = $duplicateFamily->marriage_date;
        }

        if (empty($primaryFamily->marriage_place) && !empty($duplicateFamily->marriage_place)) {
            $primaryFamily->marriage_place = $duplicateFamily->marriage_place;
        }

        if (empty($primaryFamily->divorce_date) && !empty($duplicateFamily->divorce_date)) {
            $primaryFamily->divorce_date = $duplicateFamily->divorce_date;
        }

        $primaryFamily->save();

        // Delete the duplicate family
        $duplicateFamily->delete();

        return $primaryFamily;
    }

    /**
     * Export family data.
     */
    public function exportFamilyData(Family $family): array
    {
        return [
            'family_info' => [
                'id' => $family->id,
                'marriage_date' => $family->marriage_date,
                'marriage_place' => $family->marriage_place,
                'divorce_date' => $family->divorce_date,
            ],
            'parents' => [
                'husband' => $family->husband ? [
                    'id' => $family->husband->id,
                    'name' => $family->husband->fullname(),
                    'birth_date' => $family->husband->birthday?->format('Y-m-d'),
                    'death_date' => $family->husband->deathday?->format('Y-m-d'),
                ] : null,
                'wife' => $family->wife ? [
                    'id' => $family->wife->id,
                    'name' => $family->wife->fullname(),
                    'birth_date' => $family->wife->birthday?->format('Y-m-d'),
                    'death_date' => $family->wife->deathday?->format('Y-m-d'),
                ] : null,
            ],
            'children' => $this->getFamilyChildren($family)->map(function ($child) {
                return [
                    'id' => $child->id,
                    'name' => $child->fullname(),
                    'sex' => $child->getSex(),
                    'birth_date' => $child->birthday?->format('Y-m-d'),
                    'death_date' => $child->deathday?->format('Y-m-d'),
                ];
            })->toArray(),
        ];
    }
}