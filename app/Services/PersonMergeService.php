<?php

namespace App\Services;

use App\Models\Family;
use App\Models\Person;
use App\Models\PersonAlia;
use App\Models\PersonAnci;
use App\Models\PersonAsso;
use App\Models\PersonEvent;
use App\Models\PersonLds;
use App\Models\PersonName;
use App\Models\PersonNameFone;
use App\Models\PersonSubm;
use App\Models\SourceRef;
use App\Models\VirtualEventAttendee;
use Exception;
use Illuminate\Support\Facades\DB;

class PersonMergeService
{
    /**
     * Merge $duplicate into $primary and delete $duplicate.
     * Returns the merged Person (fresh instance).
     *
     * This is conservative: it prefers existing primary fields, and only copies fields
     * when primary is empty. All related records will be reassigned to primary where safe.
     *
     * @throws Exception
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
                if (empty($primary->{$field}) && ! empty($duplicate->{$field})) {
                    $primary->{$field} = $duplicate->{$field};
                    $updated[] = $field;
                }
            }

            // Save primary if changed
            if ($updated !== []) {
                $primary->save();
            }

            $primaryId = $primary->id;
            $duplicateId = $duplicate->id;

            // Only these two tables actually have a person_id column. Every other
            // person-owned table here is GEDCOM-shaped and keys off (`group`, `gid`)
            // instead — listing them in a person_id loop made every merge die on
            // "Unknown column 'person_id'" at the second entry, so no merge has ever
            // completed. class_exists() cannot catch that: the class exists, the
            // column does not.
            $modelsWithPersonId = [
                PersonEvent::class,
                VirtualEventAttendee::class,
            ];

            foreach ($modelsWithPersonId as $model) {
                $model::where('person_id', $duplicateId)->update(['person_id' => $primaryId]);
            }

            // The GEDCOM tables: `group` names the kind of record the row hangs off
            // ('indi' = a person) and `gid` is that person's id. One shape, so one
            // loop — repointing gid is what actually moves a name/alias/ordinance/
            // citation from the duplicate onto the primary. Going through the models
            // keeps the tenant global scope on these updates.
            $modelsKeyedByGid = [
                PersonName::class,
                PersonNameFone::class,
                PersonAlia::class,
                PersonLds::class,
                PersonSubm::class,
                PersonAnci::class,
                PersonAsso::class,
                SourceRef::class,
            ];

            foreach ($modelsKeyedByGid as $model) {
                $model::where('group', PersonAsso::GROUP_INDI)
                    ->where('gid', $duplicateId)
                    ->update(['gid' => $primaryId]);
            }

            // Two of those also point AT a person from the other end, in varchar
            // columns the importer fills with a person id: an association's counterpart
            // and an alias' target. A merge has to move those too or they keep naming
            // the person we are about to delete.
            PersonAsso::where('indi', (string) $duplicateId)->update(['indi' => (string) $primaryId]);
            PersonAlia::where('alia', (string) $duplicateId)->update(['alia' => (string) $primaryId]);

            // Families: if duplicate is husband/wife/child, reassign references to primary
            if (class_exists(Family::class)) {
                // husband_id
                Family::where('husband_id', $duplicateId)->update(['husband_id' => $primaryId]);
                // wife_id
                Family::where('wife_id', $duplicateId)->update(['wife_id' => $primaryId]);
            }

            // `child_in_family_id` holds a FAMILY id (Family::children() is
            // hasMany(Person::class, 'child_in_family_id')), not a person id. Matching
            // it against $duplicateId compared a family id to a person id and, on the
            // small integers these tables actually use, would reassign unrelated
            // children to whichever family shared the duplicate's id. It never fired
            // only because the person_id loop above threw first.
            //
            // What a merge actually owes here: the duplicate's own parentage, if the
            // primary has none.
            if (empty($primary->child_in_family_id) && ! empty($duplicate->child_in_family_id)) {
                $primary->child_in_family_id = $duplicate->child_in_family_id;
                $primary->save();
            }

            // Move tree positions if missing
            if (empty($primary->tree_position_x) && ! empty($duplicate->tree_position_x)) {
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
