<?php

namespace App\Services\RecordMatcher\Providers;

use App\Models\Person;
use App\Support\Unavailable;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        // config(key, default) only falls back when the key is ABSENT. These keys
        // are declared in config/services.php as env(...) with no default, so an
        // unset variable makes the key present-but-null, the default never fires,
        // and null hits a typed string property — the provider cannot even be
        // constructed. Cast instead of defaulting.
        $this->apiKey = (string) config('services.ancestry.api_key');
        $this->baseUrl = (string) (config('services.ancestry.base_url') ?: 'https://api.ancestry.com/v1');
        $this->timeout = (int) (config('services.ancestry.timeout') ?: 30);
    }

    /**
     * Search Ancestry for matching records.
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
            Log::warning('Ancestry API key not configured');

            return new Unavailable('Ancestry is not configured.');
        }

        try {
            $searchParams = $this->buildSearchParams($person);
            $response = $this->performSearch($searchParams);

            if (! is_array($response)) {
                Log::error('Ancestry returned an unreadable response', ['person_id' => $person->id]);

                return new Unavailable('Ancestry returned an unreadable response.');
            }

            return $this->parseResponse($response);
        } catch (Exception $e) {
            Log::error('Ancestry search failed', [
                'person_id' => $person->id,
                'error' => $e->getMessage(),
            ]);

            return new Unavailable('Ancestry request failed: '.$e->getMessage());
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
        }

        // GEDCOM names these birthday_plac/deathday_plac, and they are plain
        // varchars. There are no birthplace/deathplace columns or relations, so
        // these guards were always false and the location was never sent — every
        // provider search ran without a place, silently returning weaker matches.
        if ($person->birthday_plac) {
            $params['birthLocation'] = $person->birthday_plac;
        }

        // Death information
        if ($person->deathday) {
            $params['deathYear'] = $person->deathday->format('Y');
        }

        if ($person->deathday_plac) {
            $params['deathLocation'] = $person->deathday_plac;
        }

        // Gender
        if ($person->sex) {
            $params['gender'] = $person->sex;
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
            ->get($this->baseUrl.'/search/records', $searchParams);

        if (! $response->successful()) {
            throw new Exception('Ancestry API request failed: '.$response->status());
        }

        // Return the decoded body as-is (null/scalar for an unreadable response);
        // the caller distinguishes that from a valid empty result.
        return $response->json();
    }

    /**
     * Parse API response into standardized format.
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
     */
    public function getName(): string
    {
        return 'Ancestry';
    }

    /**
     * Check if provider is configured.
     */
    public function isConfigured(): bool
    {
        return $this->apiKey !== '' && $this->apiKey !== '0';
    }
}
