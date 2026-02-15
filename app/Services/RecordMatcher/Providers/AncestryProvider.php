<?php

namespace App\Services\RecordMatcher\Providers;

use App\Models\Person;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Ancestry provider for searching external genealogy records.
 * Integrates with Ancestry API to find potential matches.
 */
class AncestryProvider implements ExternalRecordProviderInterface
{
    protected string $apiKey;
    protected string $baseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey = config('services.ancestry.api_key', '');
        $this->baseUrl = config('services.ancestry.base_url', 'https://api.ancestry.com/v1');
        $this->timeout = config('services.ancestry.timeout', 30);
    }

    /**
     * Search Ancestry for matching records.
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

        // If API key is not configured, return empty results
        if (empty($this->apiKey)) {
            Log::warning('Ancestry API key not configured');
            return [];
        }

        try {
            $searchParams = $this->buildSearchParams($person);
            $response = $this->performSearch($searchParams);
            
            return $this->parseResponse($response);
        } catch (Exception $e) {
            Log::error('Ancestry search failed', [
                'person_id' => $person->id,
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Build search parameters from person data.
     *
     * @param Person $person
     * @return array
     */
    protected function buildSearchParams(Person $person): array
    {
        $params = [];

        // Name parameters
        if ($person->first_name) {
            $params['givenName'] = $person->first_name;
        }
        if ($person->last_name) {
            $params['surname'] = $person->last_name;
        }

        // Birth information
        if ($person->birthday) {
            $params['birthYear'] = $person->birthday->format('Y');
        }

        if ($person->birthplace) {
            $params['birthLocation'] = $person->birthplace->place ?? null;
        }

        // Death information
        if ($person->deathday) {
            $params['deathYear'] = $person->deathday->format('Y');
        }

        if ($person->deathplace) {
            $params['deathLocation'] = $person->deathplace->place ?? null;
        }

        // Gender
        if ($person->sex) {
            $params['gender'] = $person->sex;
        }

        return array_filter($params);
    }

    /**
     * Perform the actual API search.
     *
     * @param array $searchParams
     * @return array
     */
    protected function performSearch(array $searchParams): array
    {
        $response = Http::timeout($this->timeout)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])
            ->get($this->baseUrl . '/search/records', $searchParams);

        if (!$response->successful()) {
            throw new Exception('Ancestry API request failed: ' . $response->status());
        }

        return $response->json() ?? [];
    }

    /**
     * Parse API response into standardized format.
     *
     * @param array $response
     * @return array
     */
    protected function parseResponse(array $response): array
    {
        $results = [];
        $records = $response['records'] ?? $response['searchResults'] ?? [];

        foreach ($records as $record) {
            $person = $record['person'] ?? $record;
            
            $results[] = [
                'id' => $person['id'] ?? $person['personId'] ?? null,
                'external_id' => $person['id'] ?? $person['personId'] ?? null,
                'tree_id' => $person['treeId'] ?? null,
                'first_name' => $person['givenName'] ?? $person['firstName'] ?? '',
                'last_name' => $person['surname'] ?? $person['lastName'] ?? '',
                'birth_year' => $person['birthYear'] ?? null,
                'birth_date' => $person['birthDate'] ?? null,
                'birth_place' => $person['birthLocation'] ?? $person['birthPlace'] ?? null,
                'death_year' => $person['deathYear'] ?? null,
                'death_date' => $person['deathDate'] ?? null,
                'death_place' => $person['deathLocation'] ?? $person['deathPlace'] ?? null,
                'gender' => $person['gender'] ?? $person['sex'] ?? null,
                'parents' => $person['parents'] ?? null,
                'spouse' => $person['spouse'] ?? null,
                'children' => $person['children'] ?? [],
                'source_url' => $person['recordUrl'] ?? $person['url'] ?? null,
                'tree_name' => $person['treeName'] ?? null,
                'tree_owner' => $person['treeOwner'] ?? null,
            ];
        }

        return $results;
    }

    /**
     * Get provider name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Ancestry';
    }

    /**
     * Check if provider is configured.
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }
}
