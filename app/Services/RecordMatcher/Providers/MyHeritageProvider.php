<?php

namespace App\Services\RecordMatcher\Providers;

use App\Models\Person;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * MyHeritage provider for searching external genealogy records.
 * Integrates with MyHeritage API to find potential matches.
 */
class MyHeritageProvider implements ExternalRecordProviderInterface
{
    protected string $apiKey;
    protected string $baseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey = config('services.myheritage.api_key', '');
        $this->baseUrl = config('services.myheritage.base_url', 'https://api.myheritage.com/v1');
        $this->timeout = config('services.myheritage.timeout', 30);
    }

    /**
     * Search MyHeritage for matching records.
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
            Log::warning('MyHeritage API key not configured');
            return [];
        }

        try {
            $searchParams = $this->buildSearchParams($person);
            $response = $this->performSearch($searchParams);
            
            return $this->parseResponse($response);
        } catch (Exception $e) {
            Log::error('MyHeritage search failed', [
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
            $params['first_name'] = $person->first_name;
        }
        if ($person->last_name) {
            $params['last_name'] = $person->last_name;
        }

        // Birth information
        if ($person->birthday) {
            $params['birth_year'] = $person->birthday->format('Y');
            $params['birth_date'] = $person->birthday->format('Y-m-d');
        }

        if ($person->birthplace) {
            $params['birth_place'] = $person->birthplace->place ?? null;
        }

        // Death information
        if ($person->deathday) {
            $params['death_year'] = $person->deathday->format('Y');
            $params['death_date'] = $person->deathday->format('Y-m-d');
        }

        if ($person->deathplace) {
            $params['death_place'] = $person->deathplace->place ?? null;
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
            ->get($this->baseUrl . '/search/persons', $searchParams);

        if (!$response->successful()) {
            throw new Exception('MyHeritage API request failed: ' . $response->status());
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
        $persons = $response['persons'] ?? $response['results'] ?? [];

        foreach ($persons as $person) {
            $results[] = [
                'id' => $person['id'] ?? $person['person_id'] ?? null,
                'external_id' => $person['id'] ?? $person['person_id'] ?? null,
                'tree_id' => $person['tree_id'] ?? null,
                'first_name' => $person['first_name'] ?? $person['given_name'] ?? '',
                'last_name' => $person['last_name'] ?? $person['surname'] ?? '',
                'birth_year' => $person['birth_year'] ?? null,
                'birth_date' => $person['birth_date'] ?? null,
                'birth_place' => $person['birth_place'] ?? null,
                'death_year' => $person['death_year'] ?? null,
                'death_date' => $person['death_date'] ?? null,
                'death_place' => $person['death_place'] ?? null,
                'gender' => $person['gender'] ?? $person['sex'] ?? null,
                'parents' => $person['parents'] ?? null,
                'spouse' => $person['spouse'] ?? null,
                'children' => $person['children'] ?? [],
                'source_url' => $person['url'] ?? $person['link'] ?? null,
                'tree_name' => $person['tree_name'] ?? null,
                'tree_owner' => $person['tree_owner'] ?? null,
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
        return 'MyHeritage';
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
