<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Person;
use App\Models\PersonEvent;
use App\Models\SourceRef;
use App\Models\Tree;

/**
 * Completeness reporting (SCOPE §14).
 *
 * Tenant note: Person / PersonEvent / SourceRef all use BelongsToTenant, so
 * these reads are scoped to the acting user's current team when called inside
 * an authed Filament tenant context. Outside auth (queue/console) the scope
 * no-ops and the numbers span every team — call these from the app panel only.
 */
class CompletenessService
{
    /**
     * Pedigree completeness: how much of a tree's ancestry is filled in.
     *
     * Walks the ancestor pedigree from the tree's root person and counts how many
     * of the theoretical ancestor slots exist. A pedigree has 2^g slots at
     * generation g, so for N generations there are (2^(N+1) - 2) slots total.
     * completeness = filled_slots / total_slots * 100.
     *
     * @return array{generations:int,total_slots:int,filled_slots:int,missing_parents:int,completeness:float,root_person_id:int|null}
     */
    public function treeCompleteness(Tree $tree, int $generations = 4): array
    {
        $root = $tree->rootPerson;
        $totalSlots = (2 ** ($generations + 1)) - 2;

        $stats = ['filled' => 0, 'missing' => 0];
        if ($root) {
            $this->walkAncestors($root, 0, $generations, [], $stats);
        }

        return [
            'generations' => $generations,
            'total_slots' => $totalSlots,
            'filled_slots' => $stats['filled'],
            'missing_parents' => $stats['missing'],
            'completeness' => $totalSlots > 0 ? round($stats['filled'] / $totalSlots * 100, 1) : 0.0,
            'root_person_id' => $root?->id,
        ];
    }

    /**
     * Recursively count filled vs missing parent slots.
     *
     * $path is the chain of ancestor ids from the root to the current person; a
     * person reappearing in its own path is a data cycle, so we stop there.
     * Distinct paths sharing a person (legitimate pedigree collapse) still count
     * that person in each position it occupies.
     *
     * @param  list<int>  $path
     * @param  array{filled:int,missing:int}  $stats
     */
    private function walkAncestors(Person $person, int $depth, int $maxGen, array $path, array &$stats): void
    {
        if ($depth >= $maxGen) {
            return;
        }
        if (in_array($person->id, $path, true)) {
            return; // cycle guard
        }
        $path[] = $person->id;

        $family = $person->childInFamily;

        foreach ([$family?->husband, $family?->wife] as $parent) {
            if ($parent) {
                $stats['filled']++;
                $this->walkAncestors($parent, $depth + 1, $maxGen, $path, $stats);
            } else {
                $stats['missing']++;
            }
        }
    }

    /**
     * Source completeness: share of people and events that carry at least one
     * source citation.
     *
     * The GEDCOM importer links a SOUR reference to a record through
     * source_ref.group + source_ref.gid: group 'indi' with gid = people.id for a
     * person-level source, and group 'indi_even' with gid = person_events.id for
     * an event-level source. We treat a record as sourced when such a row exists.
     *
     * @return array{overall:float,persons:array{total:int,with_source:int,percentage:float},events:array{total:int,with_source:int,percentage:float}}
     */
    public function sourceCompleteness(): array
    {
        $personsTotal = Person::count();
        $personsWithSource = Person::whereIn(
            'id',
            SourceRef::query()->where('group', 'indi')->pluck('gid')
        )->count();

        $eventsTotal = PersonEvent::count();
        $eventsWithSource = PersonEvent::whereIn(
            'id',
            SourceRef::query()->where('group', 'indi_even')->pluck('gid')
        )->count();

        $total = $personsTotal + $eventsTotal;
        $withSource = $personsWithSource + $eventsWithSource;

        return [
            'overall' => $this->percentage($withSource, $total),
            'persons' => [
                'total' => $personsTotal,
                'with_source' => $personsWithSource,
                'percentage' => $this->percentage($personsWithSource, $personsTotal),
            ],
            'events' => [
                'total' => $eventsTotal,
                'with_source' => $eventsWithSource,
                'percentage' => $this->percentage($eventsWithSource, $eventsTotal),
            ],
        ];
    }

    private function percentage(int $part, int $whole): float
    {
        return $whole > 0 ? round($part / $whole * 100, 1) : 0.0;
    }
}
