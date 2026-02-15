<?php

namespace App\Services\RecordMatcher\Providers;

use App\Models\Person;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * FamilySearch provider for searching external genealogy records.
 * Integrates with FamilySearch API to find potential matches.
 */
class FamilySearchProvider implements ExternalRecordProviderInterface
{
    protected string $apiKey;
    protected string $baseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey = config('services.familysearch.api_key', '');
        $this->baseUrl = config('services.familysearch.base_url', 'https://api.familysearch.org/platform');
        $this->timeout = config('services.familysearch.timeout', 30);
    }

    /**
     * Search FamilySearch for matching records.
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
            Log::warning('FamilySearch API key not configured');
            return [];
        }

        try {
            $searchParams = $this->buildSearchParams($person);
            $response = $this->performSearch($searchParams);
            
            return $this->parseResponse($response);
        } catch (Exception $e) {
            Log::error('FamilySearch search failed', [
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
            $params['birthDate'] = $person->birthday->format('Y-m-d');
        }

        if ($person->birthplace) {
            $params['birthPlace'] = $person->birthplace->place ?? null;
        }

        // Death information
        if ($person->deathday) {
            $params['deathYear'] = $person->deathday->format('Y');
            $params['deathDate'] = $person->deathday->format('Y-m-d');
        }

        if ($person->deathplace) {
            $params['deathPlace'] = $person->deathplace->place ?? null;
        }

        // Gender
        if ($person->sex) {
            $params['gender'] = $person->sex === 'M' ? 'male' : 'female';
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
            ->get($this->baseUrl . '/tree/search', $searchParams);

        if (!$response->successful()) {
            throw new Exception('FamilySearch API request failed: ' . $response->status());
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
        $entries = $response['entries'] ?? [];

        foreach ($entries as $entry) {
            $person = $entry['content'] ?? [];
            $gedcomx = $person['gedcomx'] ?? [];
            $personData = $gedcomx['persons'][0] ?? [];

            $results[] = [
                'id' => $personData['id'] ?? null,
                'external_id' => $personData['id'] ?? null,
                'tree_id' => null,
                'first_name' => $this->extractName($personData, 'given'),
                'last_name' => $this->extractName($personData, 'surname'),
                'birth_year' => $this->extractYear($personData, 'birth'),
                'birth_date' => $this->extractDate($personData, 'birth'),
                'birth_place' => $this->extractPlace($personData, 'birth'),
                'death_year' => $this->extractYear($personData, 'death'),
                'death_date' => $this->extractDate($personData, 'death'),
                'death_place' => $this->extractPlace($personData, 'death'),
                'gender' => $personData['gender']['type'] ?? null,
                'parents' => null,
                'spouse' => null,
                'children' => [],
                'source_url' => $entry['links']['person']['href'] ?? null,
                'tree_name' => 'FamilySearch Family Tree',
                'tree_owner' => null,
            ];
        }

        return $results;
    }

    protected function extractName(array $personData, string $type): ?string
    {
        $names = $personData['names'][0]['nameForms'][0]['parts'] ?? [];
        foreach ($names as $part) {
            if (isset($part['type']) && strtolower($part['type']) === strtolower($type)) {
                return $part['value'] ?? null;
            }
        }
        return null;
    }

    protected function extractYear(array $personData, string $eventType): ?int
    {
        $date = $this->extractDate($personData, $eventType);
        if ($date && preg_match('/(\d{4})/', $date, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    protected function extractDate(array $personData, string $eventType): ?string
    {
        $facts = $personData['facts'] ?? [];
        foreach ($facts as $fact) {
            if (isset($fact['type']) && stripos($fact['type'], $eventType) !== false) {
                return $fact['date']['original'] ?? null;
            }
        }
        return null;
    }

    protected function extractPlace(array $personData, string $eventType): ?string
    {
        $facts = $personData['facts'] ?? [];
        foreach ($facts as $fact) {
            if (isset($fact['type']) && stripos($fact['type'], $eventType) !== false) {
                return $fact['place']['original'] ?? null;
            }
        }
        return null;
    }

    /**
     * Get provider name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'FamilySearch';
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
