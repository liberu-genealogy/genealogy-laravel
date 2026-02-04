<?php

namespace App\Services\RecordMatcher\Providers;

use App\Models\Person;

interface ExternalRecordProviderInterface
{
    /**
     * Search external records for a given local person.
     *
     * Return an iterable/array of associative arrays representing candidate records,
     * each containing at least a provider-specific id and fields used for scoring.
     *
     * Example candidate:
     * [
     *   'id' => 'FS-12345',
     *   'first_name' => 'John',
     *   'last_name' => 'Doe',
     *   'birth_year' => 1879,
     *   'birth_place' => 'County X',
     *   ...
     * ]
     *
     * @param Person|int $localPerson
     * @return array
     */
    public function search($localPerson): array;
}
