<?php

namespace App\Services;

use App\Models\Person;
use App\Models\SmartMatch;
use App\Models\User;
use App\Services\RecordMatcher\Providers\AncestryProvider;
use App\Services\RecordMatcher\Providers\FamilySearchProvider;
use App\Services\RecordMatcher\Providers\MyHeritageProvider;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SmartMatchingService
{
    protected array $providers = [];

    public function __construct()
    {
        $this->initializeProviders();
    }

    /**
     * Initialize external genealogy providers
     */
    protected function initializeProviders(): void
    {
        // Add MyHeritage provider
        $myHeritage = new MyHeritageProvider;
        if ($myHeritage->isConfigured()) {
            $this->providers['myheritage'] = $myHeritage;
        }

        // Add Ancestry provider
        $ancestry = new AncestryProvider;
        if ($ancestry->isConfigured()) {
            $this->providers['ancestry'] = $ancestry;
        }

        // Add FamilySearch provider
        $familySearch = new FamilySearchProvider;
        if ($familySearch->isConfigured()) {
            $this->providers['familysearch'] = $familySearch;
        }

        Log::info('Smart matching providers initialized', [
            'configured_providers' => array_keys($this->providers),
        ]);
    }

    /**
     * People in the user's current team who are missing at least one parent —
     * no child_in_family link at all, or a family with a missing husband/wife.
     *
     * @return Collection<int, Person>
     */
    public function findPeopleWithMissingParents(User $user): Collection
    {
        return Person::where('team_id', $user->current_team_id)
            // Group the OR so it can't break out of the team_id filter and pull
            // in other teams' people.
            ->where(function ($query): void {
                $query->whereNull('child_in_family_id')
                    ->orWhereHas('childInFamily', function ($q): void {
                        $q->whereNull('husband_id')->orWhereNull('wife_id');
                    });
            })
            ->get();
    }

    /**
     * Find smart matches for user's unknown ancestors
     */
    public function findSmartMatches(User $user): Collection
    {
        // Get people with missing parent information
        $peopleWithMissingParents = $this->findPeopleWithMissingParents($user);

        $matches = collect();

        foreach ($peopleWithMissingParents as $person) {
            $potentialMatches = $this->searchPublicTrees($person);

            foreach ($potentialMatches as $match) {
                $smartMatch = SmartMatch::create([
                    'user_id' => $user->id,
                    'person_id' => $person->id,
                    'external_tree_id' => $match['tree_id'],
                    'external_person_id' => $match['person_id'],
                    'match_source' => $match['source'],
                    'record_type_id' => $match['record_type_id'] ?? null,
                    'record_category' => $match['record_category'] ?? null,
                    'match_data' => $match['data'],
                    'search_criteria' => $match['search_criteria'] ?? null,
                    'confidence_score' => $match['confidence_score'],
                    'status' => 'pending',
                ]);

                $matches->push($smartMatch);
            }
        }

        return $matches;
    }

    /**
     * Search public trees for potential matches
     */
    private function searchPublicTrees(Person $person): array
    {
        // With no provider configured this used to fall back to a simulation that
        // invented matches — names, birth/death dates and places, parents, a spouse,
        // children and a plausible-looking source_url — which findSmartMatches()
        // then persisted as SmartMatch rows and the UI presented as real records
        // from Ancestry/MyHeritage/FindMyPast. In a genealogy product that is
        // fabricated ancestry, so an unconfigured install now returns nothing.
        if ($this->providers === []) {
            Log::warning('No genealogy providers configured; smart matching returns no matches', [
                'person_id' => $person->id,
            ]);

            return [];
        }

        $matches = $this->searchUsingProviders($person);

        // Sort by confidence score
        usort($matches, fn (array $a, array $b) => $b['confidence_score'] <=> $a['confidence_score']);

        // Return top 10 matches
        return array_slice($matches, 0, 10);
    }

    /**
     * Search using configured external providers
     */
    private function searchUsingProviders(Person $person): array
    {
        $matches = [];

        foreach ($this->providers as $providerName => $provider) {
            try {
                $candidates = $provider->search($person);

                foreach ($candidates as $candidate) {
                    $confidence = $this->calculateMatchConfidence($person, $candidate);

                    if ($confidence >= 0.6) { // 60% confidence threshold
                        $matches[] = [
                            'tree_id' => $candidate['tree_id'] ?? null,
                            'person_id' => $candidate['id'] ?? $candidate['external_id'] ?? null,
                            'source' => $providerName,
                            'confidence_score' => $confidence,
                            'data' => $candidate,
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::error("Provider search failed: {$providerName}", [
                    'person_id' => $person->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $matches;
    }

    /**
     * Calculate confidence score for a match
     */
    private function calculateMatchConfidence(Person $person, array $match): float
    {
        $score = 0.0;
        $factors = 0.0;

        // Providers return first_name/last_name (see ExternalRecordProviderInterface),
        // never a composed 'name'. Reading $match['name'] raised an undefined-key
        // warning and passed null into calculateNameSimilarity's string parameter,
        // so every real candidate threw a TypeError that searchUsingProviders caught
        // and logged as "Provider search failed" — the provider path could not score
        // a single match. Only the simulation, which did emit 'name', ever worked.
        $candidateName = trim(($match['first_name'] ?? '').' '.($match['last_name'] ?? ''));
        if ($candidateName === '') {
            $candidateName = (string) ($match['name'] ?? '');
        }

        // Name similarity (40% weight)
        if ($candidateName !== '') {
            $score += $this->calculateNameSimilarity($person->fullname(), $candidateName) * 0.4;
            $factors += 0.4;
        }

        // Birth date similarity (30% weight)
        if ($person->birthday && ! empty($match['birth_date'])) {
            $birthDate = $this->parseDate($match['birth_date']);
            if ($birthDate instanceof DateTime) {
                $score += $this->calculateDateSimilarity($person->birthday, $birthDate) * 0.3;
                $factors += 0.3;
            }
        }

        // Death date similarity (20% weight)
        if ($person->deathday && ! empty($match['death_date'])) {
            $deathDate = $this->parseDate($match['death_date']);
            if ($deathDate instanceof DateTime) {
                $score += $this->calculateDateSimilarity($person->deathday, $deathDate) * 0.2;
                $factors += 0.2;
            }
        }

        // Birth place (10% weight). This factor used to be calculateContextSimilarity(),
        // which returned random_int(30, 90) / 100 — a random tenth of the confidence of
        // every match, real ones included. Compare the places we actually hold instead,
        // and skip the factor when either side is missing rather than invent a number.
        if ($person->birthday_plac && ! empty($match['birth_place'])) {
            $score += $this->calculateNameSimilarity($person->birthday_plac, (string) $match['birth_place']) * 0.1;
            $factors += 0.1;
        }

        return $factors > 0.0 ? $score / $factors : 0.0;
    }

    /**
     * Provider dates are free text ("1879", "12 May 1879", ""), so a bare
     * new DateTime() on them throws for anything it cannot parse.
     */
    private function parseDate(mixed $value): ?DateTime
    {
        if (! is_string($value) && ! is_int($value)) {
            return null;
        }

        try {
            return new DateTime((string) $value);
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Calculate name similarity
     */
    private function calculateNameSimilarity(string $name1, string $name2): float
    {
        $name1 = strtolower(trim($name1));
        $name2 = strtolower(trim($name2));

        if ($name1 === $name2) {
            return 1.0;
        }

        $maxLength = max(strlen($name1), strlen($name2));
        if ($maxLength === 0) {
            return 0.0;
        }

        $distance = levenshtein($name1, $name2);

        return max(0, 1 - ($distance / $maxLength));
    }

    /**
     * Calculate date similarity
     */
    private function calculateDateSimilarity(DateTime $date1, DateTime $date2): float
    {
        $diff = abs($date1->getTimestamp() - $date2->getTimestamp());
        $daysDiff = $diff / (60 * 60 * 24);

        if ($daysDiff === 0) {
            return 1.0;
        }
        if ($daysDiff <= 365) {
            return 0.9;
        }
        if ($daysDiff <= 1825) {
            return 0.7;
        }

        return 0.3;
    }
}
