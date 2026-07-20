<?php

namespace App\Services\RecordMatcher\Providers;

use App\Models\Person;
use App\Support\Unavailable;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        // config(key, default) only falls back when the key is ABSENT. These keys
        // are declared in config/services.php as env(...) with no default, so an
        // unset variable makes the key present-but-null, the default never fires,
        // and null hits a typed string property — the provider cannot even be
        // constructed. Cast instead of defaulting.
        $this->apiKey = (string) config('services.familysearch.api_key');
        $this->baseUrl = (string) (config('services.familysearch.base_url') ?: 'https://api.familysearch.org/platform');
        $this->timeout = (int) (config('services.familysearch.timeout') ?: 30);
    }

    /**
     * Search FamilySearch for matching records.
     *
     * @param  Person|int  $localPerson
     */
    public function search($localPerson): array|Unavailable
    {
        $person = is_int($localPerson) ? Person::find($localPerson) : $localPerson;

        if (! $person) {
            return [];
        }

        if ($this->apiKey === '' || $this->apiKey === '0') {
            Log::warning('FamilySearch API key not configured');

            return new Unavailable('FamilySearch is not configured.');
        }

        try {
            $searchParams = $this->buildSearchParams($person);
            $response = $this->performSearch($searchParams);

            if (! is_array($response)) {
                Log::error('FamilySearch returned an unreadable response', ['person_id' => $person->id]);

                return new Unavailable('FamilySearch returned an unreadable response.');
            }

            return $this->parseResponse($response);
        } catch (Exception $e) {
            Log::error('FamilySearch search failed', [
                'person_id' => $person->id,
                'error' => $e->getMessage(),
            ]);

            return new Unavailable('FamilySearch request failed: '.$e->getMessage());
        }
    }

    /**
     * Build search parameters from person data.
     */
    protected function buildSearchParams(Person $person): array
    {
        $params = [];

        // Name parameters
        if ($person->givn) {
            $params['givenName'] = $person->givn;
        }
        if ($person->surn) {
            $params['surname'] = $person->surn;
        }

        // Birth information
        if ($person->birthday) {
            $params['birthYear'] = $person->birthday->format('Y');
            $params['birthDate'] = $person->birthday->format('Y-m-d');
        }

        // See AncestryProvider: the columns are birthday_plac/deathday_plac
        // (GEDCOM), plain varchars. birthplace/deathplace never existed, so this
        // guard was always false and the place was never sent.
        if ($person->birthday_plac) {
            $params['birthPlace'] = $person->birthday_plac;
        }

        // Death information
        if ($person->deathday) {
            $params['deathYear'] = $person->deathday->format('Y');
            $params['deathDate'] = $person->deathday->format('Y-m-d');
        }

        if ($person->deathday_plac) {
            $params['deathPlace'] = $person->deathday_plac;
        }

        // Gender
        if ($person->sex) {
            $params['gender'] = $person->sex === 'M' ? 'male' : 'female';
        }

        return array_filter($params);
    }

    /**
     * Perform the actual API search.
     */
    protected function performSearch(array $searchParams): mixed
    {
        $response = Http::timeout($this->timeout)
            ->withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Accept' => 'application/json',
            ])
            ->get($this->baseUrl.'/tree/search', $searchParams);

        if (! $response->successful()) {
            throw new Exception('FamilySearch API request failed: '.$response->status());
        }

        return $response->json();
    }

    /**
     * Parse API response into standardized format.
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
     */
    public function getName(): string
    {
        return 'FamilySearch';
    }

    /**
     * Check if provider is configured.
     */
    public function isConfigured(): bool
    {
        return $this->apiKey !== '' && $this->apiKey !== '0';
    }
}
