<?php

namespace App\Services;

use App\Models\Person;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class PersonMergeService
{
    /**
     * Merge $duplicate into $primary and delete $duplicate.
     * Returns the merged Person (fresh instance).
     *
     * This is conservative: it prefers existing primary fields, and only copies fields
     * when primary is empty. All related records will be reassigned to primary where safe.
     *
     * @throws \Exception
     */
    public function merge(Person $primary, Person $duplicate): Person
    {
        if ($primary->id === $duplicate->id) {
            return $primary;
        }

        return DB::transaction(function () use ($primary, $duplicate) {
            // Merge scalar fields: take non-empty from duplicate if primary empty
            $fields = [
                'givn', 'surn', 'name', 'appellative', 'email', 'phone',
                'birthday', 'deathday', 'burial_day', 'description', 'gid', 'titl',
            ];

            $updated = [];
            foreach ($fields as $field) {
                if (empty($primary->{$field}) && !empty($duplicate->{$field})) {
                    $primary->{$field} = $duplicate->{$field};
                    $updated[] = $field;
                }
            }

            // Save primary if changed
            if (!empty($updated)) {
                $primary->save();
            }

            $primaryId = $primary->id;
            $duplicateId = $duplicate->id;

            // Reassign related models that have person_id
            $modelsWithPersonId = [
                \App\Models\PersonEvent::class,
                \App\Models\PersonName::class,
                \App\Models\PersonAlia::class,
                \App\Models\PersonLds::class,
                \App\Models\PersonSubm::class,
                \App\Models\VirtualEventAttendee::class,
                \App\Models\PersonAnci::class,
                \App\Models\PersonAsso::class,
                \App\Models\PersonNameFone::class,
            ];

            foreach ($modelsWithPersonId as $model) {
                if (class_exists($model)) {
                    $model::where('person_id', $duplicateId)->update(['person_id' => $primaryId]);
                }
            }

            // Families: if duplicate is husband/wife/child, reassign references to primary
            if (class_exists(\App\Models\Family::class)) {
                // husband_id
                \App\Models\Family::where('husband_id', $duplicateId)->update(['husband_id' => $primaryId]);
                // wife_id
                \App\Models\Family::where('wife_id', $duplicateId)->update(['wife_id' => $primaryId]);
            }

            // If duplicate was child_in_family in a family, update that relation
            \App\Models\Person::where('child_in_family_id', $duplicateId)->update(['child_in_family_id' => $primaryId]);

            // Move tree positions if missing
            if (empty($primary->tree_position_x) && !empty($duplicate->tree_position_x)) {
                $primary->tree_position_x = $duplicate->tree_position_x;
                $primary->tree_position_y = $duplicate->tree_position_y;
                $primary->save();
            }

            // Merge other pivot-style relations if needed (example: many-to-many) — left as future work.

            // Finally, mark duplicate as merged and delete it
            // Option: keep a minimal tombstone record (not implemented) — we delete for now but could be changed.
            $duplicate->delete();

            return $primary->fresh();
        });
    }
}
