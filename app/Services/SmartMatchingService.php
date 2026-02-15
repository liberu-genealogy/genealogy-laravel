<?php

namespace App\Services;

use DateTime;
use App\Models\Person;
use App\Models\User;
use App\Models\SmartMatch;
use App\Services\RecordMatcher\Providers\MyHeritageProvider;
use App\Services\RecordMatcher\Providers\AncestryProvider;
use App\Services\RecordMatcher\Providers\FamilySearchProvider;
use App\Services\RecordMatcher\Providers\ExternalRecordProviderInterface;
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
        $myHeritage = new MyHeritageProvider();
        if ($myHeritage->isConfigured()) {
            $this->providers['myheritage'] = $myHeritage;
        }

        // Add Ancestry provider
        $ancestry = new AncestryProvider();
        if ($ancestry->isConfigured()) {
            $this->providers['ancestry'] = $ancestry;
        }

        // Add FamilySearch provider
        $familySearch = new FamilySearchProvider();
        if ($familySearch->isConfigured()) {
            $this->providers['familysearch'] = $familySearch;
        }

        Log::info('Smart matching providers initialized', [
            'configured_providers' => array_keys($this->providers),
        ]);
    }

    /**
     * Find smart matches for user's unknown ancestors
     */
    public function findSmartMatches(User $user): Collection
    {
        // Get people with missing parent information
        $peopleWithMissingParents = Person::whereHas('user', function ($query) use ($user) {
            $query->where('current_team_id', $user->current_team_id);
        })
        ->whereNull('child_in_family_id')
        ->orWhereHas('childInFamily', function ($query) {
            $query->whereNull('husband_id')->orWhereNull('wife_id');
        })
        ->get();

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
                    'match_data' => $match['data'],
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
        $matches = [];

        // If providers are configured, use them; otherwise fall back to simulation
        if (count($this->providers) > 0) {
            $matches = $this->searchUsingProviders($person);
        } else {
            Log::warning('No genealogy providers configured, using simulation mode');
            $matches = $this->searchUsingSimulation($person);
        }

        // Sort by confidence score
        usort($matches, function ($a, $b) {
            return $b['confidence_score'] <=> $a['confidence_score'];
        });

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
     * Fallback simulation mode when no providers are configured
     */
    private function searchUsingSimulation(Person $person): array
    {
        $matches = [];
        
        // Simulate searching different genealogy platforms
        $sources = ['familysearch', 'ancestry', 'myheritage', 'findmypast'];

        foreach ($sources as $source) {
            $sourceMatches = $this->searchSource($person, $source);
            $matches = array_merge($matches, $sourceMatches);
        }

        return $matches;
    }

    /**
     * Search a specific source for matches
     */
    private function searchSource(Person $person, string $source): array
    {
        // This would integrate with actual genealogy APIs
        // For now, we'll simulate potential matches
        
        $matches = [];
        
        // Simulate finding matches based on name and dates
        $searchTerms = [
            'name' => $person->fullname(),
            'birth_year' => $person->birthday?->format('Y'),
            'death_year' => $person->deathday?->format('Y'),
        ];

        // Simulate API response
        $simulatedMatches = $this->simulateApiResponse($person, $source);

        foreach ($simulatedMatches as $match) {
            $confidence = $this->calculateMatchConfidence($person, $match);
            
            if ($confidence >= 0.6) { // 60% confidence threshold
                $matches[] = [
                    'tree_id' => $match['tree_id'],
                    'person_id' => $match['person_id'],
                    'source' => $source,
                    'confidence_score' => $confidence,
                    'data' => $match,
                ];
            }
        }

        return $matches;
    }

    /**
     * Simulate API response from genealogy platforms
     */
    private function simulateApiResponse(Person $person, string $source): array
    {
        // This simulates what would come from real APIs
        $matches = [];
        
        // Generate some realistic-looking matches
        for ($i = 0; $i < rand(2, 8); $i++) {
            $matches[] = [
                'tree_id' => $source . '_tree_' . rand(1000, 9999),
                'person_id' => $source . '_person_' . rand(10000, 99999),
                'name' => $this->generateSimilarName($person->fullname()),
                'birth_date' => $this->generateSimilarDate($person->birthday),
                'death_date' => $this->generateSimilarDate($person->deathday),
                'birth_place' => $this->generateRandomPlace(),
                'death_place' => $this->generateRandomPlace(),
                'parents' => [
                    'father' => $this->generateRandomName('male'),
                    'mother' => $this->generateRandomName('female'),
                ],
                'spouse' => $this->generateRandomName($person->sex === 'M' ? 'female' : 'male'),
                'children' => array_map(fn() => $this->generateRandomName(), range(1, rand(0, 4))),
                'source_url' => "https://{$source}.com/tree/" . rand(1000, 9999),
                'last_updated' => now()->subDays(rand(1, 365))->format('Y-m-d'),
            ];
        }

        return $matches;
    }

    /**
     * Calculate confidence score for a match
     */
    private function calculateMatchConfidence(Person $person, array $match): float
    {
        $score = 0;
        $factors = 0;

        // Name similarity (40% weight)
        $nameSimilarity = $this->calculateNameSimilarity($person->fullname(), $match['name']);
        $score += $nameSimilarity * 0.4;
        $factors += 0.4;

        // Birth date similarity (30% weight)
        if ($person->birthday && $match['birth_date']) {
            $birthSimilarity = $this->calculateDateSimilarity($person->birthday, new DateTime($match['birth_date']));
            $score += $birthSimilarity * 0.3;
            $factors += 0.3;
        }

        // Death date similarity (20% weight)
        if ($person->deathday && $match['death_date']) {
            $deathSimilarity = $this->calculateDateSimilarity($person->deathday, new DateTime($match['death_date']));
            $score += $deathSimilarity * 0.2;
            $factors += 0.2;
        }

        // Additional context (10% weight)
        $contextScore = $this->calculateContextSimilarity($person, $match);
        $score += $contextScore * 0.1;
        $factors += 0.1;

        return $factors > 0 ? $score / $factors : 0;
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

        if ($daysDiff === 0) return 1.0;
        if ($daysDiff <= 365) return 0.9;
        if ($daysDiff <= 1825) return 0.7;
        return 0.3;
    }

    /**
     * Calculate context similarity (places, family members, etc.)
     */
    private function calculateContextSimilarity(Person $person, array $match): float
    {
        // This would compare places, family members, etc.
        // For simulation, return a random score
        return rand(30, 90) / 100;
    }

    /**
     * Helper methods for simulation
     */
    private function generateSimilarName(string $originalName): string
    {
        $names = explode(' ', $originalName);
        $variations = [];

        foreach ($names as $name) {
            // Sometimes use the exact name, sometimes a variation
            if (rand(0, 100) < 70) {
                $variations[] = $name;
            } else {
                $variations[] = $this->getNameVariation($name);
            }
        }

        return implode(' ', $variations);
    }

    private function getNameVariation(string $name): string
    {
        $variations = [
            'John' => ['Jon', 'Johnny', 'Jonathan'],
            'William' => ['Will', 'Bill', 'Billy'],
            'Elizabeth' => ['Beth', 'Liz', 'Betty'],
            'Mary' => ['Marie', 'Maria'],
            'James' => ['Jim', 'Jimmy'],
        ];

        return $variations[$name][array_rand($variations[$name])] ?? $name;
    }

    private function generateSimilarDate(?DateTime $originalDate): ?string
    {
        if (!$originalDate) return null;

        // Generate a date within 5 years of the original
        $variation = rand(-5, 5);
        return $originalDate->modify("{$variation} years")->format('Y-m-d');
    }

    private function generateRandomPlace(): string
    {
        $places = [
            'London, England',
            'Manchester, England',
            'Birmingham, England',
            'Liverpool, England',
            'Edinburgh, Scotland',
            'Glasgow, Scotland',
            'Cardiff, Wales',
            'Belfast, Northern Ireland',
            'Dublin, Ireland',
            'Cork, Ireland',
        ];

        return $places[array_rand($places)];
    }

    private function generateRandomName(string $gender = null): string
    {
        $maleNames = ['John', 'William', 'James', 'George', 'Thomas', 'Henry', 'Charles', 'Robert'];
        $femaleNames = ['Mary', 'Elizabeth', 'Sarah', 'Margaret', 'Jane', 'Catherine', 'Anne', 'Emma'];
        $surnames = ['Smith', 'Jones', 'Brown', 'Wilson', 'Taylor', 'Davies', 'Evans', 'Thomas'];

        if ($gender === 'male') {
            $firstName = $maleNames[array_rand($maleNames)];
        } elseif ($gender === 'female') {
            $firstName = $femaleNames[array_rand($femaleNames)];
        } else {
            $allNames = array_merge($maleNames, $femaleNames);
            $firstName = $allNames[array_rand($allNames)];
        }

        $surname = $surnames[array_rand($surnames)];
        return "{$firstName} {$surname}";
    }
}