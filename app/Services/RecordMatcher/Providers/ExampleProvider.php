<?php

namespace App\Services\RecordMatcher\Providers;

use App\Models\Person;

/**
 * Example provider for testing the record matching system.
 * This returns sample data for demonstration purposes.
 */
class ExampleProvider implements ExternalRecordProviderInterface
{
    /**
     * Search for matching records in the example data source.
     *
     * @param Person|int $localPerson
     * @return array
     */
    public function search($localPerson): array
    {
        $person = is_int($localPerson) ? Person::find($localPerson) : $localPerson;
        
        if (!$person) {
            return [];
        }

        // Return empty array for now - this is just a placeholder
        // Real implementation would search an actual data source
        return [];
    }
}
