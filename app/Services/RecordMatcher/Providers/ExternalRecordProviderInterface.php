<?php

declare(strict_types=1);

namespace App\Services\RecordMatcher\Providers;

use App\Models\Person;
use App\Support\Unavailable;

interface ExternalRecordProviderInterface
{
    /**
     * Search external records for a given local person.
     *
     * Returns an array of candidate records (each an associative array with at
     * least a provider id and the fields used for scoring), OR an Unavailable
     * when the provider could not search — so "we could not look" is never
     * mistaken for "we looked and found nothing". An empty array means the
     * search ran and matched nothing.
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
     * @param  Person|int  $localPerson
     */
    public function search($localPerson): array|Unavailable;
}
