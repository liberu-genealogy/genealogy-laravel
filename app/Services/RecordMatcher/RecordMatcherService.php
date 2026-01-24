<?php

namespace App\Services\RecordMatcher\Providers;

use App\Models\Person;

class ExampleProvider implements ExternalRecordProviderInterface
{
    public function search($localPerson): array
    {
        // Stub: in production replace with a real API client to FamilySearch, Ancestry, etc.
        // Return an array of candidate records.
        return [
            [
                'id' => 'example-1',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'birth_year' => 1879,
                'birth_place' => 'County X',
            ],
        ];
    }
}
