<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @extends Builder<Person>
 */
class PersonBuilder extends Builder
{
    /**
     * The vendor GEDCOM parser writes people via a bulk upsert (BatchData::upsert),
     * which bypasses Eloquent model events — so the Person `saving` hook never sees
     * imported rows. Decompose the raw slashed GEDCOM NAME ("John /Smith/", which
     * lands in `givn` with `surn` empty) here, at the one seam every imported row
     * passes through.
     *
     * @param  array<int, array<string, mixed>>  $values
     * @param  array<int, string>|string  $uniqueBy
     * @param  array<int, string>|null  $update
     */
    #[\Override]
    public function upsert(array $values, $uniqueBy, $update = null): int
    {
        foreach ($values as &$row) {
            if (array_key_exists('givn', $row)) {
                [$row['givn'], $row['surn']] = Person::decomposeSlashedName(
                    isset($row['givn']) ? (string) $row['givn'] : null,
                    isset($row['surn']) ? (string) $row['surn'] : null,
                );
            }
        }
        unset($row);

        return parent::upsert($values, $uniqueBy, $update);
    }
}
